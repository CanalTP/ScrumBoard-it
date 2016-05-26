<?php
namespace ScrumBoardItBundle\Security;

use ScrumBoardItBundle\Security\AbstractTokenAuthenticator;
use Symfony\Component\Security\Core\User\UserInterface;

class GitHubAuthenticator extends AbstractTokenAuthenticator
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $login = $user->getUsername();
        $password = $credentials['password'];
        $user->setHash("$login:$password");
        
        $url = $this->data['host'] . 'user';
        $results = $this->apiCaller->call($user, $url);
        
        if ($results['http_code'] == 200 && ! empty($results['content'])) {
            $data = $results['content'];
            $user->setEmail($data->email);
            $user->setDisplayName($data->name);
            $user->setImgUrl($data->avatar_url);
            $user->setApi($this->getApi());
            
            return true;
        }
        return false;
    }

    /**
     *
     * {@inheritDoc}
     *
     */
    protected function getApi()
    {
        return 'github';
    }
}