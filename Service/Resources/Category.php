<?php
require_once '../config.php';
require_once 'Tools/DataAccess.php';
require_once 'Tools/MySessionHandler.php';

use Tonic\Resource,
    Tonic\Response,
    Tonic\ConditionException;

/**
 * @uri /category
 * @uri /category/
 * @uri /category/:login
 */
class Category extends Resource
{
    /**
     * Reads the categories data, if there is id_category parameter sent
     * returns only one catergory with this id
     * else lists all current logged users categories
     * @method GET
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function read() {
        $id_category = $_REQUEST['id_category'];
        $session = new MySessionHandler();
        $db = new DataAccess();
        $user_id = $session->getParam('USER_AUTH_ID');
        
        $sql = 'SELECT "id_category", "name"
            FROM "Categories" 
            WHERE "author"=:author';
        if ($id_category) {
            $sql += ' AND "id_category"=:category';
        }
        
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":author", $user_id, PDO::PARAM_INT);
        if ($id_category) {
            $dbs->bindValue(":category", $id_category, PDO::PARAM_INT);
        }
        $dbs->execute();
        
        if ($dbs->rowCount() > 0) {
            $data = $dbs->fetchAll(PDO::FETCH_ASSOC);
            $result = Array();
            $result['data'] = $data;
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Problem z odczytem kategorii z bazy danych";
        }
        
        return new Response(200, $result);
    }
    
    /**
     * Creates new category
     * @method PUT
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function create() {
        $requestData = null;
        parse_str($this->request->data, $requestData);
        $name = $requestData['name'];
        $session = new MySessionHandler();
        $db = new DataAccess();
        $user_id = $session->getParam('USER_AUTH_ID');
        
        $sql = 'INSERT INTO "Categories" ("name", "author")
            VALUES (:name, :author)';
        
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":author", $user_id, PDO::PARAM_INT);
        $dbs->bindValue(":name", $name, PDO::PARAM_STR);
        $dbs->execute();
        
        if ($dbs->rowCount() > 0) {
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Problem z zapisaniem kategorii do bazy danych";
        }
        
        return new Response(200, $result);
    }
    
    /**
     * Edits category by given id
     * @method POST
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function update() {
        $requestData = null;
        parse_str($this->request->data, $requestData);
        $id_category = $requestData['id_category'];
        $name = $requestData['name'];
        
        if (!$id_category) {
            $result['success'] = false;
            $result['msg'] = "Brak id kategorii";
            return new Response(200, $result);
        }
        
        $db = new DataAccess();
        
        $sql = 'UPDATE "Categories" 
            SET "name"=:name
            WHERE "id_category"=:id';
        
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":id", $id_category, PDO::PARAM_INT);
        $dbs->bindValue(":name", $name, PDO::PARAM_STR);
        $dbs->execute();
        
        if ($dbs->rowCount() > 0) {
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Problem z zapisaniem kategorii do bazy danych";
        }
        
        return new Response(200, $result);
    }
    
    /**
     * Removes category by given id
     * @method DELETE
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function remove() {
        $requestData = null;
        parse_str($this->request->data, $requestData);
        $id_category = $requestData['id_category'];
        
        if (!$id_category) {
            $result['success'] = false;
            $result['msg'] = "Brak id kategorii";
            return new Response(200, $result);
        }
        
        $db = new DataAccess();
        
        $sql = 'DELETE FROM "Categories" 
            WHERE "id_category"=:id';
        
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":id", $id_category, PDO::PARAM_INT);
        $dbs->execute();
        
        if ($dbs->rowCount() > 0) {
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Problem z zapisaniem kategorii do bazy danych";
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
     * Post/Get only!
     */
    protected function command($command)
    {
        if (strtolower($command) != strtolower($_REQUEST['command'])) throw new Tonic\ConditionException;
    }
    
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
}
