<?php
namespace App\Commands;

use Aws\DynamoDb\DynamoDbClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command should only be used to populate the local dynamodb container
 * for development. The production table is configured in ./serverless.yml
 */
class MigrateDynamoDBCommand extends Command
{
    private DynamoDbClient $dynamoDbClient;

    public function __construct(DynamoDbClient $dynamoDbClient)
    {
        parent::__construct();
        $this->dynamoDbClient = $dynamoDbClient;
    }

    protected function configure()
    {
        $this->setName('migrate-dynamodb')
             ->setDescription('Migrate the DynamoDB database')
             ->addArgument('task', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $task = $input->getArgument('task');

        if ($task == 'up') {
            return $this->up();
        }

        // "down" not implemented yet. Just restart the container to purge the in-memory table...

        $output->writeln(sprintf('Task not configured: %s', $task));

        return 255;
    }

    private function up(): int
    {
        $created = $this->dynamoDbClient->createTable(
            [
                'TableName' => 'chessgame',
                'KeySchema' => [
                    [
                        'AttributeName' => 'id',
                        'KeyType'       => 'HASH',
                    ],
                    [
                        'AttributeName' => 'date',
                        'KeyType'       => 'SORT',
                    ],
                ],
                'AttributeDefinitions' => [
                    [
                        'AttributeName' => 'id',
                        'AttributeType' => 'S',
                    ],
                    [
                        'AttributeName' => 'date',
                        'AttributeType' => 'S',
                    ],
                ],
                'BillingMode' => 'PAY_PER_REQUEST',
            ]
        );

        dd($created);

        return 0;
    }
}
