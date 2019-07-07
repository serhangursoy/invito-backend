<?php
namespace Routes\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{

    private $http;

    public function setUp() {
        $this->http = new Client(['base_uri' => 'http://localhost:8000/']);
    }

    public function testUserFetch(){
        $response = $this->http->request('GET', 'users');
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(true), true);
        $this->assertNotEquals( 0, count($data["users"]));
    }

    public function testRegistration(){
        $response = $this->http->post("register", [
            'exceptions' => false,
            RequestOptions::JSON => ['username' => 'AutomatedUser']
        ]);
        if( $response->getStatusCode() === 200) {
            $this->assertEquals(200, $response->getStatusCode());
            $data = json_decode($response->getBody(true), true);
            $this->assertNotEquals( 0, $data["id"]);
        } else {
            $this->assertEquals(400, $response->getStatusCode());
            $data = json_decode($response->getBody(true), true);
            $this->assertEquals( "This name is already registered", $data["message"]);
        }
    }


    public function testLogin(){
        $response = $this->http->post("login", [
            RequestOptions::JSON => ['username' => 'AutomatedUser']
        ]);
        if( $response->getStatusCode() === 200) {
            $this->assertEquals(200, $response->getStatusCode());
            $data = json_decode($response->getBody(true), true);

            $this->assertNotEquals( 0, $data["id"]);
            return $data['id'];
        }
        return 0;
    }

    /**
     * @depends testLogin
     * @param $uid
     * @return array
     */
    public function testCreateInvitation($uid){
        $response = $this->http->post("invitation/create", [
            RequestOptions::JSON => ["from" => $uid, "to" => 1, "when" => "10/10/2020", "about" =>"Automated Meeting" ]
        ]);
        if( $response->getStatusCode() === 200) {
            $this->assertEquals(200, $response->getStatusCode());
            $data = json_decode($response->getBody(true), true);
            $this->assertNotEquals( 0, $data["id"]);
            $this->assertEquals( "Invitation request successful", $data["message"]);
            $result = [$uid,$data["id"]];
            return $result;
        }
    }


    /**
     * @depends testLogin
     * @param $uid
     */
    public function testGetInvitations($uid){
        $response = $this->http->get("invitation/$uid");
        if( $response->getStatusCode() === 200) {
            $this->assertEquals(200, $response->getStatusCode());
            $data = json_decode($response->getBody(true), true);
            $this->assertNotEquals( 0, count($data["owned_invitations"]));
        }
    }

    /**
     * @depends testCreateInvitation
     * @param $uid
     */
    public function testCancelInvitation($result){
        $uid = $result[0];
        $iid = $result[1];

        $response = $this->http->post("invitation/update", [
            RequestOptions::JSON => ["uid" => $uid, "iid" => $iid, "op" => "cancel"]
        ]);
        if( $response->getStatusCode() === 200) {
            $this->assertEquals(200, $response->getStatusCode());
            $data = json_decode($response->getBody(true), true);
            $this->assertEquals( "Update successful", $data["message"]);
        }
    }

    public function tearDown() {
        $this->http = null;
    }
}