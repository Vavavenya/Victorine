<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 09.05.2018
 * Time: 10:03
 */

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersTableController extends Controller
{
    /**
     * @Route("admin/UsersTable", name="Users_Table")
     */
    public function UsersTable()
    {
    	$users = $this->getDoctrine()
    	->getRepository(User::class)
    	->findAll();

    	return $this->render('admin/UsersTable.html.twig',array('users' => $users));
    }
  }