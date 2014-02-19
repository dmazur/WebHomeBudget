<?php
require_once '../config.php';
require_once 'Tools/DataAccess.php';
require_once 'Tools/MySessionHandler.php';

use Tonic\Resource,
    Tonic\Response,
    Tonic\ConditionException;

/**
 * @uri /time
 * @uri /time/
 * @uri /time/:login
 */
class Time extends Resource
{
    /**
     * Get all years and months between min and max bill date for logged user
     * @method GET
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function read() {
        $session = new MySessionHandler();
        $db = new DataAccess();
        $user_id = $session->getParam('USER_AUTH_ID');
        
        $sql = 'SELECT MIN("when") AS mindate, MAX("when") AS maxdate 
                FROM "Bills" INNER JOIN "Categories" ON (    "Categories".id_category = "Bills".category) 
                WHERE "Categories".author=:author';
        
        $dbs = $db->prepare($sql);
        $dbs->bindValue(":author", $user_id, PDO::PARAM_INT);
        $dbs->execute();
        
        if (!$dbs->execute()) {
            $result['success'] = false;
            $result['msg'] = "Problem with fetch data from database";
            return new Response(200, $result);
        }
        
        $dates = $dbs->fetch(PDO::FETCH_ASSOC);
        $maxDate = new DateTime($dates['maxdate']);
        $minDate = new DateTime($dates['mindate']);
        $data = array();

        // generate array with all years and months between those two dates
        while ($maxDate->getTimestamp() >= $minDate->getTimestamp()) {
            if ($data[count($data)-1]['number'] === $minDate->format('Y')) {
                $data[count($data)-1]['months'][] = array(
                    "name" => $minDate->format('F'),
                    "number" => $minDate->format('n')
                );
                $minDate->add(new DateInterval('P1M'));
            }
            else {
                $data[] = array(
                    'number' => $minDate->format('Y'),
                    'months' => array()
                );
            }
        }

        $result['success'] = true;
        $result['data'] = $data;
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
