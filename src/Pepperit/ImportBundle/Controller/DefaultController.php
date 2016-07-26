<?php

namespace Pepperit\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Pepperit\ImportBundle\Entity\Product;
use Pepperit\ImportBundle\Entity\Category;

use Doctrine\ORM\Query\ResultSetMapping;

class DefaultController extends Controller
{
    public $newFiles = "C:\Import\New\*.csv";
    public $doneDirectory = "C:\Import\Done\\";

    public function indexAction()
    {
        $countNewFiles = count(glob($this->newFiles));

        return $this->render(
            'PepperitImportBundle:Default:index.html.twig',
            array(
                'countNewFiles'=>$countNewFiles)
        );
    }

    /**
     * @Route("/upload", name="upload")
     */
    public function uploadAction()
    {
        set_time_limit(1000);
        $message = '';

        $files = glob($this->newFiles);

        $insertCounter = 0;
        $headers = array('reference', 'name', 'description', 'quantity', 'price', 'color', 'category_id');
        $categoryRepository = $this->getDoctrine()->getManager()->getRepository('PepperitImportBundle:Category');

        foreach ($files as $file) {
            $fileName = basename($file);
            $handle = fopen($file, "r");

            if ($handle !== false) {
                $em = $this->getDoctrine()->getEntityManager();
                $connection = $em->getConnection();

                $flag = true;
                while (($data = fgetcsv($handle)) !== false) {
                    //tam, kad praskippintu pirma headeriu eilute ir neinsertintu i db
                    if ($flag) {
                        $flag = false;
                        continue;
                    }

                    $statement = $connection->prepare("INSERT INTO product(reference, name, description, quantity, price, color, category_id)
                        VALUES(
                              :reference, :name, :description, :quantity, :price, :color, :category_id
                         )
                         ON DUPLICATE KEY UPDATE reference = :reference, name = :name, description = :description, quantity = :quantity, price = :price, color = :color, category_id = :category_id
                         ");

                        $productCategory = end($data);
                        $category = $categoryRepository->findOneByName($productCategory);

                        //priskirti category_id arba sukurti nauja kategorija ir priskirti
                    if (isset($category)) {
                        $statement->bindValue(end($headers), $category->getId());
                    } else {
                        $category = new Category;
                        $category->setName($productCategory);
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($category);
                        $em->flush();
                        $statement->bindValue(end($headers), $category->getId());
                    }

                    for ($i=0; $i<count($headers)-1; $i++) {
                        $statement->bindValue($headers[$i], $data[$i]);
                    }

                    if ($statement->execute()) {
                        $insertCounter++;
                    }
                }
                fclose($handle);
                $message = 'Successfully uploaded/updated ' .$insertCounter .' products';
            } else {
                $message = 'Could not open file: ' . $file;
            }
            $this->createDirectory($this->doneDirectory);
            rename($file, $this->doneDirectory . date("YmdHis") . '-' . $fileName);
        }
        return $this->render(
            'PepperitImportBundle:Default:upload.html.twig',
            array(
                'message'=>$message,
                'files'=>$files)
        );
    }

    public function createDirectory($directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory);
        }
    }
}
