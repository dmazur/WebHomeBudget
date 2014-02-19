<?php
require_once '../config.php';
require_once 'Tools/DataAccess.php';
require_once 'Tools/MySessionHandler.php';

use Tonic\Resource,
    Tonic\Response,
    Tonic\ConditionException;

/**
 * @uri /cyclic
 * @uri /cyclic/
 * @uri /cyclic/:login
 */
class Cyclic extends Resource
{
    /**
     * Reads the cyclic data, if there is id_cyclic parameter sent
     * returns only one cyclic with this id
     * else lists all current logged users cyclic
     * @method GET
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function read() {
        $id_cyclic = $_REQUEST['id_cyclic'];
        $session = new MySessionHandler();
        $db = new DataAccess();
        $user_id = $session->getParam('USER_AUTH_ID');

        $sql = 'SELECT "id_cyclic", "description", "value", "category", "when", "from", "to"
            FROM "Cyclic"
            INNER JOIN "Categories" ON ("Categories".id_category = "Cyclic".category) 
            WHERE "Categories".author=:author';
        if ($id_cyclic) {
            $sql += ' AND "Cyclic".id_cyclic = :cyclic';
        }
        
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":author", $user_id, PDO::PARAM_INT);
        if ($id_cyclic) {
            $dbs->bindValue(":cyclic", $id_cyclic, PDO::PARAM_INT);
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
            $result['msg'] = "Problem with writing cyclic to database";
        }
        
        return new Response(200, $result);
    }
    
    /**
     * Creates new cyclic
     * @method PUT
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function create() {
        $requestData = null;
        parse_str($this->request->data, $requestData);

        $description = $requestData['description'];
        $value = $requestData['value'];
        $category_id = $requestData['category'];
        $when = $requestData['when'];
        $from = $requestData['from'];
        $to = $requestData['to'];

        $session = new MySessionHandler();
        $db = new DataAccess();
        $user_id = $session->getParam('USER_AUTH_ID');
        $sql = 'INSERT INTO "Cyclic" ("description", "value", "category", "when", "from", "to")
            VALUES (:description, :value, :category, :when, :from, :to)';
        


        $dbs = $db->prepare($sql);
        $dbs->bindValue(":category", $category_id, PDO::PARAM_INT);
        $dbs->bindValue(":value", implode('.', explode(',', $value)), PDO::PARAM_STR);
        $dbs->bindValue(":description", $description, PDO::PARAM_STR);
        $dbs->bindValue(":when", $when, PDO::PARAM_STR);
        $dbs->bindValue(":from", $from, PDO::PARAM_STR);
        $dbs->bindValue(":to", $to, PDO::PARAM_STR);
        $dbs->execute();
        
        if ($dbs->rowCount() > 0) {
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Problem with saving cyclic to database";
        }
        
        return new Response(200, $result);
    }
    
    /**
     * Edits cyclic by given id
     * @method POST
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function update() {
        $requestData = null;
        parse_str($this->request->data, $requestData);
        $id_cyclic = $requestData['id_cyclic'];
        $description = $requestData['description'];
        $value = $requestData['value'];
        $category = $requestData['category'];
        $when = $requestData['when'];
        $from = $requestData['from'];
        $to = $requestData['to'];

        if (!$id_cyclic) {
            $result['success'] = false;
            $result['msg'] = "You lost cyclic id, you bastard";
            return new Response(200, $result);
        }
        
        $db = new DataAccess();
        
        $sql = 'UPDATE "Cyclic" 
            SET "description"= :description, "value" = :value, "category" = :category, "when" = :when, "from" = :from, "to" = :to
            WHERE "id_cyclic"=:id';
        
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":id", $id_cyclic, PDO::PARAM_INT);
        $dbs->bindValue(":value", implode('.', explode(',', $value)), PDO::PARAM_STR);
        $dbs->bindValue(":category", $category, PDO::PARAM_STR);
        $dbs->bindValue(":description", $description, PDO::PARAM_STR);
        $dbs->bindValue(":when", $when, PDO::PARAM_STR);
        $dbs->bindValue(":from", $from, PDO::PARAM_STR);
        $dbs->bindValue(":to", $to, PDO::PARAM_STR);
        $dbs->execute();
        
        if ($dbs->rowCount() > 0) {
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Problem with write cyclic to database, look at category.";
        }
        
        return new Response(200, $result);
    }
    
    /**
     * Removes cyclic by given id
     * @method DELETE
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function remove() {
        $requestData = null;
        parse_str($this->request->data, $requestData);
        $id_cyclic = $requestData['id_cyclic'];
        
        if (!$id_cyclic) {
            $result['success'] = false;
            $result['msg'] = "You lost cyclic id";
            return new Response(200, $result);
        }
        
        $db = new DataAccess();
        
        $sql = 'DELETE FROM "Cyclic" 
            WHERE "id_cyclic"=:id';
        
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":id", $id_cyclic, PDO::PARAM_INT);
        $dbs->execute();
        
        if ($dbs->rowCount() > 0) {
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "I can delete cyclic from database";
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
