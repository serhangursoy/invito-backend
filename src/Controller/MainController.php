<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\User;
use App\Repository\InvitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController {

    public function index(){
        return new Response("<body><h1>Hello. It appears that you are lost. Check documentation please.</h1></body>");
    }

    public function RegisterUser(Request $request){
        $data = $request->get("username");
        $query = $this->getDoctrine()->getRepository(User::class)->findOneBy(array("name" => $data));
        if($query) {
            $resp = new JsonResponse(array('message' => 'This name is already registered'), 400);
        } else {
            $man = $this->getDoctrine()->getManager();
            $newUser = new User();
            $newUser->name = $data;
            $man->persist($newUser);
            $man->flush();
            $resp = new JsonResponse(array('id' => $newUser->getId(),'message' => 'User registration successful'));
        }
        return $resp;
    }

    public function LoginUser(Request $request){
        $data = $request->get("username");
        $query = $this->getDoctrine()->getRepository(User::class)->findOneBy(array("name" => $data));
        if($query) {
            $resp = new JsonResponse(array('id' => $query->getId(), 'message' => 'User login successful'));
        } else {
            $resp = new JsonResponse(array('message' => 'Wrong username!'), 404);
        }
        return $resp;
    }

    public function CreateInvitation(Request $request){
        $ownerId = $request->get("from");
        $toId = $request->get("to");
        $date = $request->get("when");
        $about = $request->get("about");
        if($ownerId && $toId && $date) {
            $inv = new Invitation();
            $inv->setActive(true);
            $inv->setDate($date);
            $inv->setFromId($ownerId);
            $inv->setToId($toId);
            $inv->setReqAccepted(false);
            $inv->setReqDeclined(false);
            $inv->setDesciption($about);
            $man = $this->getDoctrine()->getManager();
            $man->persist($inv);
            $man->flush();
            $resp = new JsonResponse(array('id' => $inv->getId(),'message' => 'Invitation request successful'));
        } else {
            $resp = new JsonResponse(array('message' => 'Invalid parameters!'), 400);
        }
        return $resp;
    }

    public function UpdateInvitation(Request $request){
        $reqId = $request->get("iid");
        $userId = $request->get("uid"); // No need for that for now..
        $op = $request->get("op");
        if($reqId && $userId && $op){
            $doc = $this->getDoctrine();
            $man = $this->getDoctrine()->getManager();
            $inv = $doc->getRepository(Invitation::class)->findPossibleInvitations($reqId,$userId, $op === "cancel"? true:false);
            if($inv) {
                if ($op === "accept") {
                    $inv->reqAccepted = true;
                    $inv->reqDeclined = false;
                } else if ($op === "decline") {
                    $inv->reqDeclined = true;
                    $inv->reqAccepted = false;
                } else if ($op === "cancel") {
                    $inv->active = false;
                }
                $man->merge($inv);
                $man->flush();
                $resp = new JsonResponse(array("message" => "Update successful"));
            } else {
                $resp = new JsonResponse(array('message' => 'Invalid parameters. Wrong matching'), 400);
            }
        } else {
            $resp = new JsonResponse(array('message' => 'Invalid parameters'), 400);
        }

        return $resp;
    }

    public function FindInvitation(Request $request){
        $reqId = $request->get("uid");
        if($reqId){
            $doc = $this->getDoctrine();
            $queryOwn = $doc->getRepository(Invitation::class)->findBy(array("fromId" => $reqId, "active"=>true));
            $queryReq = $doc->getRepository(Invitation::class)->findBy(array("toId" => $reqId,"active"=>true));
            $resp = new JsonResponse(array("owned_invitations" => $queryOwn,"requested_invitations" => $queryReq));
        } else {
            $resp = new JsonResponse(array('id' => $reqId,'message' => 'Invalid parameters'), 400);
        }

        return $resp;
    }

    public function GetAllUserNames(){
        $query = $this->getDoctrine()->getRepository(User::class)->findAll();
        $resp = new JsonResponse(array('users' => $query,'message' => 'User request successful'));
        return $resp;
    }
}