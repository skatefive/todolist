<?php
// src/Ens/JobeetBundle/DataFixtures/ORM/LoadTaskData.php
namespace Ens\JobeetBundle\DataFixtures\ORM;
 
 use Doctrine\Common\Persistence\ObjectManager;
 use Doctrine\Common\DataFixtures\AbstractFixture;
 use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
 use Ens\JobeetBundle\Entity\Task;


class LoadTaskData extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $em)
  {
    $dueDate = new \DateTime(); 
    $dueDate->setTimestamp(strtotime( ' +1 days' ));

    $task = new Task();
    $task->setName('Jookse');
    $task->setStatus(1);
    $task->setDueDate($dueDate);
    
    $em->persist($task);

    $task = new Task();
    $task->setName('Ära jookse');
    $task->setStatus(2);
    $task->setDueDate($dueDate);

    $em->persist($task);

    $task = new Task();
    $task->setName('Ära jookse');
    $task->setStatus(3);
    $task->setDueDate($dueDate);

    $em->persist($task);


    $dueDate = new \DateTime(); 
    $dueDate->setTimestamp(strtotime( ' +2 days' ));

    $task = new Task();
    $task->setName('Ära jookse');
    $task->setStatus(1);
    $task->setDueDate($dueDate);

    $em->persist($task);    


    $task = new Task();
    $task->setName('Ära jookse');
    $task->setStatus(2);
    $task->setDueDate($dueDate);

    $em->persist($task);


    $dueDate = new \DateTime(); 
    $dueDate->setTimestamp(strtotime( ' +2 hours' ));

    $task = new Task();
    $task->setName('Tähtaeg kohe');
    $task->setStatus(1);
    $task->setDueDate($dueDate);

    $em->persist($task);    
        

    // $programming = new Task();
    // $programming->setName('Programming');
 
    // $manager = new Task();
    // $manager->setName('Manager');
 
    // $administrator = new Task();
    // $administrator->setName('Administrator');
 
    $em->flush();
 

  }
 
  public function getOrder()
  {
    return 1; // the order in which fixtures will be loaded
  }
}