<?php
use Phalcon\Http\Response;
$app->get(
    '/api/v1/personList',
    function () use ($app) {
        $phql = 'SELECT * FROM MyModels\Person ORDER BY name';

        $persons = $app->modelsManager->executeQuery($phql);

        $data = [];

        foreach ($persons as $person) {
            $data[] = [
                'id'   => $person->id,
                'name' => $person->name,
                'email' => $person->email,
            ];
        }

        echo json_encode($data);
    }
);

$app->get(
    '/api/v1/person/{id:[0-9]+}',
    function ($id) use ($app) {
        $phql = 'SELECT * FROM MyModels\Person WHERE id = :id:';

        $person = $app->modelsManager->executeQuery(
            $phql,
            [
                'id' => $id,
            ]
        )->getFirst();

        $response = new Response();

        if ($person === false) {
            $response->setJsonContent(
                [
                    'status' => 'NOT-FOUND'
                ]
            );
        } else {
            $response->setJsonContent(
                [
                    'status' => 'FOUND',
                    'data'   => [
                        'id'   => $person->id,
                        'name' => $person->name,
                        'email' => $person->email
                    ]
                ]
            );
        }

        return $response;
    }
);


$app->post(
    '/api/v1/person',
    function () use ($app) {
        $person = $app->request->getJsonRawBody();

        $phql = 'INSERT INTO MyModels\Person (name,email) VALUES (:name:, :email:)';

        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                'name' => $person->name,
                'email' => $person->email,

            ]
        );

        $response = new Response();

        // Check if the insertion was successful
        if ($status->success() === true) {
            // Change the HTTP status
            $response->setStatusCode(201, 'Created');

            $person->id = $status->getModel()->id;

            $response->setJsonContent(
                [
                    'status' => 'OK',
                    'data'   => $person,
                ]
            );
        } else {
            // Change the HTTP status
            $response->setStatusCode(409, 'Conflict');

            // Send errors to the client
            $errors = [];

            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }

            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => $errors,
                ]
            );
        }

        return $response;
    }
);


$app->put(
    '/api/v1/person/{id:[0-9]+}',
    function ($id) use ($app) {
        $person = $app->request->getJsonRawBody();

        $phql = 'UPDATE MyModels\Person SET name = :name:, email = :email: WHERE id = :id:';

        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                'id'   => $id,
                'name' => $person->name,
                'email' => $person->email,
            ]
        );

        // Create a response
        $response = new Response();

        // Check if the insertion was successful
        if ($status->success() === true) {
            $response->setJsonContent(
                [
                    'status' => 'OK'
                ]
            );
        } else {
            // Change the HTTP status
            $response->setStatusCode(409, 'Conflict');

            $errors = [];

            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }

            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => $errors,
                ]
            );
        }

        return $response;
    }

);

$app->delete(
    '/api/v1/person/{id:[0-9]+}',
    function ($id) use ($app) {
        $phql = 'DELETE FROM MyModels\Person WHERE id = :id:';

        $status = $app->modelsManager->executeQuery(
            $phql,
            [
                'id' => $id,
            ]
        );

        // Create a response
        $response = new Response();

        if ($status->success() === true) {
            $response->setJsonContent(
                [
                    'status' => 'OK'
                ]
            );
        } else {
            // Change the HTTP status
            $response->setStatusCode(409, 'Conflict');

            $errors = [];

            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }

            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => $errors,
                ]
            );
        }

        return $response;
    }
);