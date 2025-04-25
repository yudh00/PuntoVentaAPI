<?php
    namespace App\controllers;

    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Container\ContainerInterface;

    use PDO;

    class Producto{
        protected $container;

        public function __construct(ContainerInterface $c){
            $this->container = $c;
        }

        public function read(Request $request, Response $response, $args){
            $sql= "SELECT * FROM productos ";

           // $sql= "SELECT * FROM productos WHERE id = :id ;";

           
            if(isset($args['id'])){
                $sql.="WHERE id = :id ";
                
            }

            $sql .=" LIMIT 0,5;";
            $con=  $this->container->get('base_datos');
            $query = $con->prepare($sql);

            if(isset($args['id'])){
                $query->execute(['id' => $args['id']]);
            }else{
                $query->execute();
            }
            
            $res= $query->fetchAll();

            $status= $query->rowCount()> 0 ? 200 : 204;

            $query=null;
            $con=null;


            $response->getbody()->write(json_encode($res));


            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($status);
        }

        public function create(Request $request, Response $response, $args){
            
            $body= json_decode($request->getBody());

           

            $campos="";
            foreach($body as $key => $value){
                $campos.= $key.', ';
              };
              $campos= substr($campos, 0, -2);

              $params="";  
              foreach($body as $key => $value){
                $params .= ":" .$key.', ';
              };
                $params= substr($params, 0, -2);

              /*
              $valores="";  
              foreach($body as $key => $value){
                $valores .= $value.', ';
              };
              $valores= substr($valores, 0, -2);
              */

            
 
            $sql = "INSERT INTO productos ( $campos) VALUES($params);";
           

            $con=  $this->container->get('base_datos');
            $query = $con->prepare($sql);



            
            foreach($body as $key => $value){
                $TIPO= gettype($value)=="integer" ? PDO::PARAM_INT : PDO::PARAM_STR;

                $value=filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);

                $query->bindValue($key, $value, $TIPO);
            };

            $query->execute();
            $status= $query->rowCount()> 0 ? 201 : 409;

            $query=null;
            $con=null;


            return $response ->withStatus($status);
        }

        public function update(Request $request, Response $response, $args){
            $body= json_decode($request->getBody());

            $sql= "UPDATE productos SET ";

            foreach($body as $key => $value){
                $sql.= $key.' = :'.$key.', ';
              };
              $sql= substr($sql, 0, -2);

              $sql.= " WHERE id = :id;";


            $con=  $this->container->get('base_datos');
            $query = $con->prepare($sql);


            foreach($body as $key => $value){
                if($key=="id"){
                    continue;
                }
                $TIPO= gettype($value)=="integer" ? PDO::PARAM_INT : PDO::PARAM_STR;

                $value=filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);

                $query->bindValue($key, $value, $TIPO);
            };

            if(isset($args['id'])){
                $query->bindValue('id', (int)$args['id'], PDO::PARAM_INT);
            }else{
                return $response ->withStatus(400);
            }

            //var_dump($query->errorInfo());
            
            $query->execute();
            //var_dump($query->errorInfo());
            
            //die();
            
            //var_dump($query->rowCount());
            
            //die();
            
            $status= $query->rowCount()> 0 ? 200 : 409;

            //var_dump($status);
            
            //die();

            

            return $response ->withStatus($status);
        }
    }
