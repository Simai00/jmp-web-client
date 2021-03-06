<?php

namespace jmp\Services;

use DateTime;
use Exception;
use Firebase\JWT\JWT;
use jmp\Models\User;
use jmp\Utils\Optional;
use Monolog\Logger;
use PDO;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;

class Auth
{

    private $subjectIdentifier;

    /**
     * @var PDO
     */
    private $db;
    /**
     * @var array
     */
    private $appConfig;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * Auth constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->db = $container->get('database');
        $this->logger = $container->get('logger');
        $this->appConfig = $container->get('settings');

        $this->subjectIdentifier = $this->appConfig['auth']['subjectIdentifier'];
    }

    /**
     * @param array $user
     * @return string
     * @throws Exception
     */
    public function generateToken(array $user): string
    {
        $now = new DateTime();
        $future = new DateTime("now +" . $this->appConfig['jwt']['expiration'] . " minutes");

        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => base64_encode(random_bytes(16)),
            'iss' => $this->appConfig['app']['url'],
            "sub" => $user[$this->subjectIdentifier],
        ];

        $secret = $this->appConfig['jwt']['secret'];
        $token = JWT::encode($payload, $secret, "HS256");

        return $token;
    }

    /**
     * Verify the login request by username and password
     * @param $username string
     * @param $password string
     * @return Optional
     */
    public function attempt(string $username, string $password): Optional
    {
        $optional = $this->getUserByUsername($username);
        if ($optional->isFailure()) {
            // No User found
            return Optional::failure();
        }

        /** @var User $user */
        $user = $optional->getData();

        // verify password
        if (password_verify($password, $user->password)) {
            // valid password
            unset($user->password);
            return Optional::success($user);
        }

        // invalid password
        return Optional::failure();
    }

    /**
     * Returns the user if the jwt token is valid and authenticated and the subject exists
     * @param Request $request
     * @return Optional
     */
    public function requestUser(Request $request): Optional
    {
        if ($token = $request->getAttribute('token')) {
            $optional = $this->getUser($token['sub']);
            if ($optional->isFailure()) {
                return $optional;
            } else {
                $user = $optional->getData();
                unset($user->password);
                return Optional::success($user);
            }
        } else {
            return Optional::failure();
        }
    }


    /**
     * Returns the user if the jwt token is valid and authenticated and the user is an admin
     * @param Request $request
     * @return Optional
     */
    public function requestAdmin(Request $request): Optional
    {
        $optional = $this->requestUser($request);

        if ($optional->isFailure()) {
            // No token supplied or invalid username
            return $optional;
        }

        if ($optional->getData()->isAdmin === true) {
            // User has admin permissions
            return $optional;
        }

        // User hasn't admin permissions
        return Optional::failure();
    }

    /**
     * Selects the user by id
     * Note: the password is also selected and must be removed!
     * @param string $id
     * @return Optional
     */
    private function getUser(int $id): Optional
    {
        $sql = <<<SQL
SELECT user.id, username, lastname, firstname, email, password, password_change AS passwordChange, is_admin AS isAdmin
FROM user
WHERE id = :id
SQL;

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':id', $id);

        if ($stmt->execute() === false) {
            return Optional::failure();
        }

        $user = $stmt->fetch();
        if ($user === false) {
            return Optional::failure();
        }

        $user = new User($user);

        return Optional::success($user);
    }

    /**
     * Selects the user by username
     * Note: the password is also selected and must be removed!
     * @param string $username
     * @return Optional
     */
    private function getUserByUsername(string $username): Optional
    {
        $sql = <<<SQL
SELECT user.id, username, lastname, firstname, email, password, password_change AS passwordChange, is_admin AS isAdmin
FROM user
WHERE username = :username
SQL;

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':username', $username);

        if ($stmt->execute() === false) {
            return Optional::failure();
        }

        $user = $stmt->fetch();
        if ($user === false) {
            return Optional::failure();
        }

        $user = new User($user);

        return Optional::success($user);
    }

}
