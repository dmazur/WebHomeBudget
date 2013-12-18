<?php
require_once '../config.php';
require_once 'Tools/DataAccess.php';
require_once 'Tools/MySessionHandler.php';

use Tonic\Resource,
    Tonic\Response,
    Tonic\ConditionException;

/**
 * The obligitory Hello World example
 *
 * The @uri annotation routes requests that match that URL to this resource. Multiple
 * annotations allow this resource to match multiple URLs.
 *
 * @uri /user
 * @uri /user/:login
 */
class User extends Resource
{
    /**
     * @method POST
     * @provides application/json
     * @json
     * @command login
     * @return Tonic\Response
     */
    public function login()
    {
        $login = $_REQUEST['login'];
        $pass = $_REQUEST['pass'];
        
        $db = new DataAccess();
        $session = new MySessionHandler();
        //return print_r($_REQUEST, true);
        $dbs = $db->prepare('SELECT "id_user", "login", "pass", "email" FROM "Users" WHERE "login"=:login AND "pass"=:pass');
        $dbs->bindValue(":login", $login, PDO::PARAM_STR);
        $dbs->bindValue(":pass", md5($pass.'4de821b132e2c393f7c52bf6e41a4331'), PDO::PARAM_STR);
        $dbs->execute();
        
        if ($dbs->rowCount() > 0) {
            $result = Array();
            $data = $dbs->fetch(PDO::FETCH_ASSOC);
            $session->setParam('USER_AUTH_LOGIN', $login);
            $session->setParam('USER_AUTH_ID', $data['id_user']);
            $result['success'] = true;
        }
        else {
            $result = Array();
            $result['success'] = false;
        }
        
        return new Response(200, $result);
    }
    
    /**
     * @method POST
     * @provides application/json
     * @json
     * @command logout
     * @return Tonic\Response
     */
    public function logout() {
        $session = new MySessionHandler();
        if($session->isParam('USER_AUTH_LOGIN')) {
            $session->destroy();
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result = Array();
            $result['success'] = false;
        }
        return new Response(200, $result);
    }
    
    /**
     * @method POST
     * @provides application/json
     * @json
     * @command getLogin
     * @return Tonic\Response
     */
    public function getLogin() {
        $session = new MySessionHandler();
        if($session->isParam('USER_AUTH_LOGIN')) {
            $result = Array();
            $result['success'] = true;
            $result['login'] = $session->getParam('USER_AUTH_LOGIN');
        }
        else {
            $result = Array();
            $result['success'] = false;
        }
        return new Response(200, $result);
    }
    
    /**
     * @method POST
     * @provides application/json
     * @json
     * @command isLogged
     * @return Tonic\Response
     */
    public function isLogged() {
        $session = new MySessionHandler();
        if($session->isParam('USER_AUTH_LOGIN')) {
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result = Array();
            $result['success'] = false;
        }
        return new Response(200, $result);
    }
    
    /**
     * @method POST
     * @provides application/json
     * @json
     * @command changePassword
     * @return Tonic\Response
     */
    public function changePassword() {
        $old = $_REQUEST['oldPassword'];
        $new = $_REQUEST['newPassword1'];
        $login = $_REQUEST['login'];
        $session = new MySessionHandler();
        $db = new DataAccess();
        
        $dbs = $db->prepare(
            'SELECT "pass" 
            FROM "Users" 
            WHERE "login"=:login'
        );
        $dbs->bindValue(":login", $login, PDO::PARAM_STR);
        $dbs->execute();
        $data = $dbs->fetch(PDO::FETCH_ASSOC);
        
        if ($dbs->rowCount() < 1) {
            $result = Array();
            $result['msg'] = "Błąd podczas pobierania danych użytkownika";
            $result['success'] = false;
            return new Response(200, $result);
        }
        
        if ($data['pass'] !== md5($old.'4de821b132e2c393f7c52bf6e41a4331')) {
            $result = Array();
            $result['msg'] = "Złe poprzednie hasło";
            $result['success'] = false;
            return new Response(200, $result);
        }
        
        $dbs = $db->prepare(
            'UPDATE "Users"
            SET "pass"=:pass
            WHERE "login"=:login'
        );
        $dbs->bindValue(":pass", md5($new.'4de821b132e2c393f7c52bf6e41a4331'), PDO::PARAM_STR);
        $dbs->bindValue(":login", $login, PDO::PARAM_STR);
        $dbs->execute();
        
        if ($dbs->rowCount() < 1) {
            $result = Array();
            $result['msg'] = "Błąd podczas zmiany hasła użytkownika";
            $result['success'] = false;
            return new Response(200, $result);
        }
        
        $data = $dbs->fetch(PDO::FETCH_ASSOC);
        
        $result = Array();
        $result['data'] = $data;
        $result['success'] = true;
        
        return new Response(200, $result);
    }
    
    /**
     * @method GET
     * @provides application/json
     * @command getOtherUsers
     * @json
     * @return Tonic\Response
     */
    public function getOtherUsers() {
        $session = new MySessionHandler();
        $login = $session->getParam('USER_AUTH_LOGIN');
        $db = new DataAccess();
        if($session->isParam('USER_AUTH_LOGIN')) {
            $dbs = $db->prepare(
                'SELECT "id_user", "login", "email"
                FROM "Users" 
                WHERE "login"!=:login'
            );
            $dbs->bindValue(":login", $login, PDO::PARAM_STR);
            $dbs->execute();
            $data = $dbs->fetchAll(PDO::FETCH_ASSOC);
            $result = Array();
            $result['data'] = $data;
            $result['success'] = true;
        }
        else {
            $result = Array();
            $result['success'] = false;
        }
        return new Response(200, $result);
    }
    
    /**
     * @method GET
     * @provides application/json
     * @json
     * @return Tonic\Response
     */
    public function getUserData() {
        $login = $_REQUEST['login'];
        $session = new MySessionHandler();
        $db = new DataAccess();
        if($session->isParam('USER_AUTH_LOGIN')) {
            $dbs = $db->prepare(
                'SELECT "id_user", "login", "email"
                FROM "Users" 
                WHERE "login"=:login'
            );
            $dbs->bindValue(":login", $login, PDO::PARAM_STR);
            $dbs->execute();
            $data = $dbs->fetch(PDO::FETCH_ASSOC);
            $result = Array();
            $result['data'] = $data;
            $result['success'] = true;
        }
        else {
            $result = Array();
            $result['success'] = false;
        }
        return new Response(200, $result);
    }
    
    /**
     * Creates new user
     * @method POST
     * @command create
     * @provides application/json
     * @json
     * @return Tonic\Response
     */
    public function create() {
        $requestData = null;
        parse_str($this->request->data, $requestData);
        $login = $requestData['login'];
        $pass = $requestData['pass'];
        $email = $requestData['email'];
        $db = new DataAccess();
        
        $sql = 'INSERT INTO "Users" ("login", "pass", "email")
            VALUES (:login, :pass, :email)';
        
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":login", $login, PDO::PARAM_STR);
        $dbs->bindValue(":pass", md5($pass.'4de821b132e2c393f7c52bf6e41a4331'), PDO::PARAM_STR);
        $dbs->bindValue(":email", $email, PDO::PARAM_STR);
        
        if ($dbs->execute()) {
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Problem z zarejestrowaniem nowego użytkownika!";
        }
        
        return new Response(200, $result);
    }
    
    /**
     * @method PUT
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function editUser() {
        parse_str($this->request->data, $post_vars);
        $login = $post_vars['login'];
        $id_user = $post_vars['id_user'];
        $email = $post_vars['email'];
        $session = new MySessionHandler();
        $db = new DataAccess();
        
        $dbs = $db->prepare(
            'UPDATE "Users"
            SET "login"=:login, "email"=:email
            WHERE "id_user"=:id_user'
        );
        $dbs->bindValue(":login", $login, PDO::PARAM_STR);
        $dbs->bindValue(":id_user", $id_user, PDO::PARAM_INT);
        $dbs->bindValue(":email", $email, PDO::PARAM_STR);
        if ($dbs->execute()) {
            $data = $dbs->fetch(PDO::FETCH_ASSOC);
            $result = Array();
            //$result['data'] = $data;
            $result['data'] = $dbs->errorCode();
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Problem z edytowaniem użytkownika";
        }
            
        return new Response(200, $result);
    }
    
    /**
     * Condition method to secure resources
     */
    protected function secure() {
        $session = new MySessionHandler();
        parse_str($this->request->data, $post_vars);
        $android = $post_vars['android'];
        if ($android) {
            //android authorisation
            return;
        }
        else {
            if ($session->isParam('USER_AUTH_LOGIN')) return;
        }
        throw new Tonic\UnauthorizedException;
    }
    
    /**
     * Condition method for above methods.
     *
     * Only allow specific :command parameter to access the method
     */
    protected function command($command)
    {
        if (strtolower($command) != strtolower($_REQUEST['command'])) throw new Tonic\ConditionException;
    }

//    /**
//     * @method GET
//     * @lang fr
//     * @param  str $name
//     * @return str
//     */
//    public function sayHelloInFrench($name = 'Monde')
//    {
//        return 'Bonjour '.$name;
//    }
//
//    /**
//     * The @priority annotation makes this method take priority over the above method.
//     *
//     * The custom @only annotation requires the matching class method to execute without
//     * throwing an exception allowing the addition of an arbitary condition to this method.
//     *
//     * @method GET
//     * @priority 2
//     * @only deckard
//     * @return str
//     */
//    public function replicants()
//    {
//        return 'Replicants are like any other machine - they\'re either a benefit or a hazard.';
//    }
//
//    /**
//     * @method GET
//     * @priority 2
//     * @only roy
//     * @return str
//     */
//    public function iveSeenThings()
//    {
//        return 'I\'ve seen things you people wouldn\'t believe.';
//    }
//
//    /**
//     * Condition method for above methods.
//     *
//     * Only allow specific :name parameter to access the method
//     */
//    protected function only($allowedName)
//    {
//        if (strtolower($allowedName) != strtolower($this->name)) throw new ConditionException;
//    }
//
//    /**
//     * The @provides annotation makes method only match requests that have a suitable accept
//     * header or URL extension (ie: /hello.json) and causes the response to automatically
//     * contain the correct content-type response header.
//     *
//     * @method GET
//     * @provides application/json
//     * @json
//     * @return Tonic\Response
//     */
//    public function sayHelloComputer()
//    {
//        return new Response(200, array(
//            'hello' => $this->name,
//            'url' => $this->app->uri($this, $this->name)
//        ));
//    }
//
    /**
     * Condition method to turn output into JSON
     */
    protected function json()
    {
        $this->before(function ($request) {
            if ($request->contentType == "application/json") {
                $request->data = json_decode($request->data);
            }
        });
        $this->after(function ($response) {
            $response->contentType = "application/json";
            if (isset($_GET['jsonp'])) {
                $response->body = $_GET['jsonp'].'('.json_encode($response->body).');';
            } else {
                $response->body = json_encode($response->body);
            }
        });
    }
//
//    /**
//     * All HTTP methods are supported. The @accepts annotation makes method only match if the
//     * request body content-type matches.
//     *
//     * curl -i -H "Content-Type: application/json" -X POST -d '{"hello": "computer"}' http://localhost/www/tonic/web/hello.json
//     *
//     * @method POST
//     * @accepts application/json
//     * @provides application/json
//     * @json
//     * @return Response
//     */
//    public function feedTheComputer()
//    {
//        return new Response(200, $this->request->data);
//    }

}
