<?php
/**
 * Created by PhpStorm.
 * User: dominik
 * Date: 04.12.18
 * Time: 18:31
 */

namespace JMP\Services;


use JMP\Models\EventType;
use Psr\Container\ContainerInterface;

class EventTypeService
{
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * EventService constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->db = $container->get('database');
    }

    /**
     * @param int $eventTypeId
     * @return EventType
     */
    public function getEventTypeByEvent(int $eventTypeId)
    {
        $sql = <<< SQL
SELECT *
FROM event_type
WHERE id = :eventTypeId
SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':eventTypeId', $eventTypeId);
        $stmt->execute();
        return new EventType($stmt->fetch());
    }
}