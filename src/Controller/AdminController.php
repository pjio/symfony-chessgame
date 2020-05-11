<?php
namespace App\Controller;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController
{
    private const TABLE_NAME = 'chessgame';

    private Marshaler $marshaler;
    private DynamoDbClient $dynamoDbClient;

    public function __construct(DynamoDbClient $dynamoDbClient)
    {
        $this->dynamoDbClient = $dynamoDbClient;
        $this->marshaler      = new Marshaler();
    }

    /**
     * @Route("/admin/add", name="admin_add")
     */
    public function add(): Response
    {
        $dataset = [
            'id'   => 'abcdef',
            'date' => '2020-06-03',
            'info' => [
                'board' => 'much board',
            ],
        ];

        $item = [
            'TableName' => self::TABLE_NAME,
            'Item'      => $this->marshaler->marshalItem($dataset),
        ];

        $result = $this->dynamoDbClient->putItem($item);

        dd($result);

        return new Response('Todo 1');
    }

    /**
     * @Route("/admin/list", name="admin_list")
     */
    public function list(): Response
    {
        $query = [
            'TableName'                 => self::TABLE_NAME,
            'ProjectionExpression'      => 'id, #dt, info.board',
            'KeyConditionExpression'    => 'id = :id',
            'ExpressionAttributeNames'  => ['#dt' => 'date'],
            'ExpressionAttributeValues' => $this->marshaler->marshalItem([':id' => 'abcdef']),
        ];

        $result = $this->dynamoDbClient->query($query);

        foreach ($result['Items'] as $item) {
            $chessgame = $this->marshaler->unmarshalItem($item);
            printf('Chessgame: %s, %s, %s</br>', $chessgame['id'], $chessgame['date'], $chessgame['info']['board']);
        }

        return new Response('Todo 2');
    }
}
