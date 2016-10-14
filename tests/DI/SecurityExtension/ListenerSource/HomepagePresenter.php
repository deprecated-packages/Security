<?php

declare(strict_types=1);

namespace Symnedi\Security\Tests\DI\SecurityExtension\ListenerSource;

use Nette\Application\UI\Presenter;

final class HomepagePresenter extends Presenter
{
    public function actionDefault()
    {
        $this->terminate();
    }
}
