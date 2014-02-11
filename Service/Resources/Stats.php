<?php
require_once '../config.php';
require_once 'Tools/DataAccess.php';
require_once 'Tools/MySessionHandler.php';

use Tonic\Resource,
    Tonic\Response,
    Tonic\ConditionException;

/**
 * @uri /stats
 * @uri /stats/
 * @uri /stats/:login
 */
class Stats extends Resource
{
    /**
     * Get part in expenses by category by given time (year, month)
     * @method GET
     * @command getCategoryPartByTime
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function getCategoryPartByTime() {
        $session = new MySessionHandler();
        $db = new DataAccess();
        $user_id = $session->getParam('USER_AUTH_ID');

        $year = $_REQUEST['year'];
        $month = $_REQUEST['month'];

        $sqlMonth = ($month) ? ' AND extract(month from "when") = :month ' : '';

        $sql = 'SELECT SUM(value), category, c.name FROM "Bills" b
                INNER JOIN "Categories" c ON (c.id_category = b.category) 
                WHERE c.author=:author
                AND extract(year from "when") = :year ' .$sqlMonth.
                'GROUP BY "category", c.name';

        $dbs = $db->prepare($sql);
        $dbs->bindValue(":author", $user_id, PDO::PARAM_INT);
        $dbs->bindValue(":year", $year, PDO::PARAM_INT);
        if ($month) $dbs->bindValue(":month", $month, PDO::PARAM_INT);
        $dbs->execute();
        
        if (!$dbs->execute()) {
            $result['success'] = false;
            $result['msg'] = "Problem z odczytem z bazy danych";
            return new Response(200, $result);
        }

        $categoryPartials = $dbs->fetchAll(PDO::FETCH_ASSOC);

        // $sum = 0;
        // foreach ($categoryPartials as $category) {
        //     $sum += $category['sum'];
        // }
        // foreach ($categoryPartials as &$category) {
        //     if ($sum > 0) {
        //         $category['sum'] = $category['sum']/$sum*100;
        //     }
        // }

        // exit(print_r($categoryPartials, true));

        $result['success'] = true;
        $result['data'] = $categoryPartials;
        return new Response(200, $result);
    }

    /**
     * Get sum of values chosen catefory and year by months
     * @method GET
     * @command getCategorySumByTime
     * @provides application/json
     * @json
     * @secure
     * @return Tonic\Response
     */
    public function getCategorySumByTime() {
        $session = new MySessionHandler();
        $db = new DataAccess();
        $user_id = $session->getParam('USER_AUTH_ID');

        $year = $_REQUEST['year'];
        $category_id = $_REQUEST['category_id'];

        $sql = 'WITH months AS ( 
                    SELECT n AS month 
                    FROM generate_series(1, 12) AS x(n)
                ) 
                SELECT m.month as month, coalesce(sum(value),0) AS sum 
                FROM months m 
                LEFT JOIN "Bills" b ON m.month = extract(month from b.when) AND b.category = :category_id AND extract(year from b.when) = :year
                LEFT JOIN "Categories" c ON (c.id_category = b.category) AND c.author= :author
                GROUP BY month
                ORDER BY month';

        $dbs = $db->prepare($sql);
        $dbs->bindValue(":author", $user_id, PDO::PARAM_INT);
        $dbs->bindValue(":year", $year, PDO::PARAM_INT);
        $dbs->bindValue(":category_id", $category_id, PDO::PARAM_INT);
        $dbs->execute();
        
        if (!$dbs->execute()) {
            $result['success'] = false;
            $result['msg'] = "Problem z odczytem z bazy danych";
            return new Response(200, $result);
        }

        $data = $dbs->fetchAll(PDO::FETCH_ASSOC);

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
}
