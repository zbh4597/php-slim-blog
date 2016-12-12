<?php
class BlogController{
    protected $ci;
    protected $logger;

    public function __construct(\Slim\Container $ci) {
       $this->ci = $ci;
       $this->logger = $this->ci->logger;
    }

    public function blogs($request, $response, $args) {
        $session = $request->getAttribute('session');

        $blogs = $session['blogs'];

        return $response->withJson($blogs);
   }

   public function blog($request, $response, $args) {
        $session = $request->getAttribute('session');

        $blogs = $session['blogs'];
        $id = $args['id'];

        return $response->withJson($blogs[$id-1]);
   }

   public function addBlogs($request, $response, $args) {
       session_start();
       $session = $request->getAttribute('session');

       $blogs = $session['blogs'];
       $parsedBody = $request->getParsedBody();
       $parsedBody['id'] = count($blogs)+1;

       array_push($blogs,$parsedBody);
       return $response->withJson($blogs);;
   }

   public function updateBlog($request, $response, $args) {
       session_start();
       $id = $args['id'];

       $session = $request->getAttribute('session');

       $blogs = $session['blogs'];
       $parsedBody = $request->getParsedBody();

       for ($i=0,$len=count($blogs); $i <$len ; $i++) { 
           if($blogs[$i]['id'] === $id){
               $blogs[$i]['title'] = $parsedBody['title'];
           }
       }

       return $response->withJson($blogs);;
   }

   public function deleteBlog($request, $response, $args) {
       session_start();
       $id = $args['id'];

       $session = $request->getAttribute('session');

       $blogs = $session['blogs'];

       return $response->withJson($blogs);;
   }
}