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

        $sql = 'SELECT "id_bill", "description", "value"
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
            $result['msg'] = "Problem z odczytem rachunku z bazy danych";
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
        $category_id = $requestData['category_id'];

        $session = new MySessionHandler();
        $db = new DataAccess();
        $user_id = $session->getParam('USER_AUTH_ID');
        
        //TODO: usunąć ten komentarz
        //Tutaj trzeba by sprawdzać czy rzeczywiście categoria należy do użytkownika, ale można to zlać i dopisać do przyszłych tasków


        $sql = 'INSERT INTO "Bills" ("description", "value", "category")
            VALUES (:description, :value, :category)';
        


        $dbs = $db->prepare($sql);
        $dbs->bindValue(":category", $category_id, PDO::PARAM_INT);
        // Podobno dla parametru float używamy PARAM_STR
        $dbs->bindValue(":value", $value, PDO::PARAM_STR);
        $dbs->bindValue(":description", $description, PDO::PARAM_STR);
        $dbs->execute();
        
        if ($dbs->rowCount() > 0) {
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Problem z zapisaniem rachunku do bazy danych";
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

        if (!$id_bill) {
            $result['success'] = false;
            $result['msg'] = "Brak id rachunku, zgubiłeś!";
            return new Response(200, $result);
        }
        
        $db = new DataAccess();
        
        $sql = 'UPDATE "Bills" 
            SET "description"= :description, "value" = :value
            WHERE "id_bill"=:id';
        
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":id", $id_bill, PDO::PARAM_INT);
        $dbs->bindValue(":value", $value, PDO::PARAM_STR);
        $dbs->bindValue(":description", $description, PDO::PARAM_STR);
        $dbs->execute();
        
        if ($dbs->rowCount() > 0) {
            $result = Array();
            $result['success'] = true;
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Problem z zapisaniem zmian w rachunku do bazy danych";
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
