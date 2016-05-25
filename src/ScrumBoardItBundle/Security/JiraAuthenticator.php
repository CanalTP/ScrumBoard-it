<?php
namespace ScrumBoardItBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use ScrumBoardItBundle\Security\AbstractTokenAuthenticator;

class JiraAuthenticator extends AbstractTokenAuthenticator
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
        
        $url = $this->data['host'] . '/rest/api/latest/user?username=' . $login;
        $results = $this->apiCaller->call($user, $url);
        
        if ($results['http_code'] == 200 && ! empty($results['content'])) {
            $content = $results['content'];
            $user->setEmail($content->emailAddress);
            $user->setDisplayName($content->displayName);
            $user->setImgUrl($content->avatarUrls->{'24x24'});
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
        return 'jira';
    }
}
