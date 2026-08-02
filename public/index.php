<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();


function getDBConnection() {
    $host = 'localhost';
    $user = 'root';
    $pass = ''; 
    $dbname = 'filipino_cookbook_api'; 

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    return new PDO($dsn, $user, $pass, $options);
}


$tokenAuthMiddleware = function (Request $request, $handler) {
    $authorization = $request->getHeaderLine('Authorization');
    $expectedToken = 'Bearer dmmmsu-cookbook-token-2026'; 

    if (empty($authorization) || $authorization !== $expectedToken) {
        $response = new \Slim\Psr7\Response();
        
        $errorPayload = [
            "status" => "error",
            "message" => "Unauthorized access. Valid API token is required."
        ];
        
        $response->getBody()->write(json_encoded($errorPayload));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);  
    }

    return $handler->handle($request);
};


function json_encoded($data) {
    return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}


function getIngredientsForFood($db, $foodId) {
    $stmt = $db->prepare("
        SELECT i.ingredient_name 
        FROM ingredients i
        JOIN food_ingredients fi ON i.ingredient_id = fi.ingredient_id
        WHERE fi.food_id = ?
        ORDER BY i.ingredient_name ASC
    ");
    $stmt->execute([$foodId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}




$app->get('/', function (Request $request, Response $response) {
    $payload = [
        "message" => "Welcome to the Secured Filipino Cookbook API",
        "note" => "Use a valid Bearer token to access /api endpoints."
    ];
    $response->getBody()->write(json_encoded($payload));
    return $response->withHeader('Content-Type', 'application/json');
});


$app->group('/api', function ($group) {

    //  Get All Foods
    $group->get('/foods', function (Request $request, Response $response) {
        try {
            $db = getDBConnection();
            $stmt = $db->query("
                SELECT f.food_id, f.food_name, c.category_name, o.origin_name, f.instructions 
                FROM foods f
                LEFT JOIN categories c ON f.category_id = c.category_id
                LEFT JOIN origins o ON f.origin_id = o.origin_id
            ");
            $foods = $stmt->fetchAll();

            
            foreach ($foods as &$food) {
                $food['food_id'] = (int)$food['food_id'];
                $food['ingredients'] = getIngredientsForFood($db, $food['food_id']);
            }

            $response->getBody()->write(json_encoded($foods));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encoded(["status" => "error", "message" => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    //  Get Food by ID
    $group->get('/foods/{id}', function (Request $request, Response $response, array $args) {
        $id = $args['id'];
        try {
            $db = getDBConnection();
            $stmt = $db->prepare("
                SELECT f.food_id, f.food_name, c.category_name, o.origin_name, f.instructions 
                FROM foods f
                LEFT JOIN categories c ON f.category_id = c.category_id
                LEFT JOIN origins o ON f.origin_id = o.origin_id
                WHERE f.food_id = ?
            ");
            $stmt->execute([$id]);
            $food = $stmt->fetch();

            if (!$food) {
                $response->getBody()->write(json_encoded([
                    "status" => "error",
                    "message" => "Food not found"
                ]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
            }

            $food['food_id'] = (int)$food['food_id'];
            $food['ingredients'] = getIngredientsForFood($db, $id);

            $response->getBody()->write(json_encoded($food));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encoded(["status" => "error", "message" => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    //  Search Food by Name
    $group->get('/foods/search/{name}', function (Request $request, Response $response, array $args) {
        $name = $args['name'];
        try {
            $db = getDBConnection();
            $stmt = $db->prepare("
                SELECT f.food_id, f.food_name, c.category_name, o.origin_name, f.instructions 
                FROM foods f
                LEFT JOIN categories c ON f.category_id = c.category_id
                LEFT JOIN origins o ON f.origin_id = o.origin_id
                WHERE f.food_name LIKE ?
            ");
            $stmt->execute(["%$name%"]);
            $foods = $stmt->fetchAll();

            foreach ($foods as &$food) {
                $food['food_id'] = (int)$food['food_id'];
                $food['ingredients'] = getIngredientsForFood($db, $food['food_id']);
            }

            $response->getBody()->write(json_encoded($foods));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encoded(["status" => "error", "message" => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    //  Get All Categories
    $group->get('/categories', function (Request $request, Response $response) {
        try {
            $db = getDBConnection();
            $stmt = $db->query("SELECT * FROM categories");
            $categories = $stmt->fetchAll();

            $response->getBody()->write(json_encoded($categories));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encoded(["status" => "error", "message" => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    //  Get All Ingredients
    $group->get('/ingredients', function (Request $request, Response $response) {
        try {
            $db = getDBConnection();
            $stmt = $db->query("SELECT * FROM ingredients");
            $ingredients = $stmt->fetchAll();

            $response->getBody()->write(json_encoded($ingredients));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $response->getBody()->write(json_encoded(["status" => "error", "message" => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    //  Add New Food 
    $group->post('/foods', function (Request $request, Response $response) {
        $data = $request->getParsedBody();

        
        if (!isset($data['food_name'], $data['category_id'], $data['origin_id'], $data['instructions'])) {
            $response->getBody()->write(json_encoded(["status" => "error", "message" => "Invalid request body."]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $db = getDBConnection();
            $db->beginTransaction(); 

            
            $stmt = $db->prepare("
                INSERT INTO foods (food_name, category_id, origin_id, instructions) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['food_name'],
                $data['category_id'],
                $data['origin_id'],
                $data['instructions']
            ]);
            
            $newFoodId = $db->lastInsertId();

            
            if (isset($data['ingredient_ids']) && is_array($data['ingredient_ids'])) {
                $stmtLink = $db->prepare("INSERT INTO food_ingredients (food_id, ingredient_id) VALUES (?, ?)");
                foreach ($data['ingredient_ids'] as $ingredientId) {
                    $stmtLink->execute([$newFoodId, $ingredientId]);
                }
            }

            $db->commit();

            $response->getBody()->write(json_encoded([
                "status" => "success",
                "message" => "Food added successfully."
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $response->getBody()->write(json_encoded(["status" => "error", "message" => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

})->add($tokenAuthMiddleware); 

$app->run();