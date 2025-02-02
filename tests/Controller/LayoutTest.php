<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Component\HttpKernel\HttpKernelBrowser;

/**
 * @group integration
 */
class LayoutTest extends ControllerBaseTest
{
    public function testNavigationMenus()
    {
        $client = $this->getClientForAuthenticatedUser(User::ROLE_USER);

        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $this->getUserByRole($em, User::ROLE_USER);

        $this->request($client, '/dashboard/');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $this->assertHasMainHeader($client, $user);
        $this->assertHasSidebar($client, $user);
    }

    protected function assertHasMainHeader(HttpKernelBrowser $client, User $user)
    {
        // TODO improve me
        // main-header > a.logo
        // # href = homepage
        // && > span.logo-mini
        // && > span.logo-lg
        // && > nav.navbar.navbar-static-top
        //    && div.navbar-custom-menu

        $content = $client->getResponse()->getContent();

        $this->assertStringContainsString('<li class="dropdown user-menu">', $content);
        $this->assertStringContainsString('<a href="/en/profile/' . $user->getUsername() . '">', $content);
        $this->assertStringContainsString('<a href="/en/profile/' . $user->getUsername() . '/prefs">', $content);
        $this->assertStringContainsString('<a href="/en/logout">', $content);
    }

    protected function assertHasSidebar(HttpKernelBrowser $client, User $user)
    {
        // TODO improve me
        // aside.main-sidebar
        // && section.sidebar
        //    && ul.sidebar-menu tree
        //       && li#dashboard > a href=dashboard
        //       && li#... with links

        $content = $client->getResponse()->getContent();

        $this->assertStringContainsString('<li id="dashboard"', $content);
        $this->assertStringContainsString('<a href="/en/dashboard/">', $content);
        $this->assertStringContainsString('<span>Dashboard</span>', $content);

        $this->assertStringContainsString('<li id="timesheet"', $content);
        $this->assertStringContainsString('<a href="/en/timesheet/">', $content);
        $this->assertStringContainsString('<span>My times</span>', $content);

        $this->assertStringContainsString('<li id="calendar"', $content);
        $this->assertStringContainsString('<a href="/en/calendar/">', $content);
        $this->assertStringContainsString('<span>Calendar</span>', $content);
    }
}
