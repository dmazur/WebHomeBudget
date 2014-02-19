<?php
require_once '../config.php';
require_once 'Tools/DataAccess.php';
require_once 'Tools/MySessionHandler.php';

use Tonic\Resource,
    Tonic\Response,
    Tonic\ConditionException;

/**
 * @uri /bill
 * @uri /bill/
 * @uri /bill/:login
 */
class Bill extends Resource
{
    /**
     * Reads the bills data, if there is id_bill parameter sent
     * returns only one bill with this id
     * else lists all current logged users bills
     * @method GET
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function read() {
        $id_bill = $_REQUEST['id_bill'];
        $session = new MySessionHandler();
        $db = new DataAccess();
        $user_id = $session->getParam('USER_AUTH_ID');

        $sql = 'SELECT "id_bill", "description", "value", "category", "when"
            FROM "Bills"
            INNER JOIN "Categories" ON ("Categories".id_category = "Bills".category) 
            WHERE "Categories".author=:author';
        if ($id_bill) {
            $sql += ' AND "Bills".id_bill = :bill';
        }
        
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":author", $user_id, PDO::PARAM_INT);
        if ($id_bill) {
            $dbs->bindValue(":bill", $id_bill, PDO::PARAM_INT);
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
            $result['msg'] = "Problem with reading bill from database";
        }
        
        return new Response(200, $result);
    }
    
    /**
     * Creates new bill
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

        $session = new MySessionHandler();
        $db = new DataAccess();
        $user_id = $session->getParam('USER_AUTH_ID');
        


        $sql = 'INSERT INTO "Bills" ("description", "value", "category", "when")
            VALUES (:description, :value, :category, :when)';
        


        $dbs = $db->prepare($sql);
        $dbs->bindValue(":category", $category_id, PDO::PARAM_INT);
        $dbs->bindValue(":value", implode('.', explode(',', $value)), PDO::PARAM_STR);
        $dbs->bindValue(":description", $description, PDO::PARAM_STR);
        $dbs->bindValue(":when", $when, PDO::PARAM_STR);
        $dbs->execute();
        
        if ($dbs->rowCount() > 0) {
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Problem with writing bill to database";
        }
        
        return new Response(200, $result);
    }
    
    /**
     * Edits bill by given id
     * @method POST
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function update() {
        $requestData = null;
        parse_str($this->request->data, $requestData);
        $id_bill = $requestData['id_bill'];
        $description = $requestData['description'];
        $value = $requestData['value'];
        $category = $requestData['category'];
        $when = $requestData['when'];

        if (!$id_bill) {
            $result['success'] = false;
            $result['msg'] = "U lost bill id!";
            return new Response(200, $result);
        }
        
        $db = new DataAccess();
        
        $sql = 'UPDATE "Bills" 
            SET "description"= :description, "value" = :value, "category" = :category, "when" = :when
            WHERE "id_bill"=:id';
        
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":id", $id_bill, PDO::PARAM_INT);
        $dbs->bindValue(":value", implode('.', explode(',', $value)), PDO::PARAM_STR);
        $dbs->bindValue(":category", $category, PDO::PARAM_STR);
        $dbs->bindValue(":description", $description, PDO::PARAM_STR);
        $dbs->bindValue(":when", $when, PDO::PARAM_STR);
        $dbs->execute();
        
        if ($dbs->rowCount() > 0) {
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Problem with write bill to database check category.";
        }
        
        return new Response(200, $result);
    }
    
    /**
     * Removes bill by given id
     * @method DELETE
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function remove() {
        $requestData = null;
        parse_str($this->request->data, $requestData);
        $id_bill = $requestData['id_bill'];
        
        if (!$id_bill) {
            $result['success'] = false;
            $result['msg'] = "Brak id rachunku";
            return new Response(200, $result);
        }
        
        $db = new DataAccess();
        
        $sql = 'DELETE FROM "Bills" 
            WHERE "id_bill"=:id';
        
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":id", $id_bill, PDO::PARAM_INT);
        $dbs->execute();
        
        if ($dbs->rowCount() > 0) {
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Problem z usunięciem rachunku";
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
