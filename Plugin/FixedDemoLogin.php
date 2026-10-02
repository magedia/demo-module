<?php
declare(strict_types=1);

namespace Magedia\Demo\Plugin;

use Magento\Backend\Model\Auth;
use Magento\Framework\App\Request\Http;

/** Public demo login only; keep out of customer stores. */
final class FixedDemoLogin
{
    public function __construct(private Http $request)
    {
    }

    public function beforeLogin(Auth $subject, $username, $password): array
    {
        // Magento authenticates before forwarding /admin to auth/login.
        // This backend auth-service hook covers every public sign-in POST.
        if ($this->request->isPost()) {
            return ['demo', 'demo'];
        }

        return [$username, $password];
    }
}
