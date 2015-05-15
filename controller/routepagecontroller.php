<?php
/**
 * ownCloud - dashboard
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Florian Steffens <webmaster@freans.de>
 * @copyright Florian Steffens 2014
 */

namespace OCA\Dashboard\Controller;

use \OCP\IRequest;
use \OCP\AppFramework\Http\TemplateResponse;
use \OCP\AppFramework\Controller;


class RoutePageController extends Controller {

    private $user;

    public function __construct($appName, IRequest $request, $user){
        parent::__construct($appName, $request);
        $this->user = $user;
    }

    /**
     *
     * main index
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function index() {
        $params = array(
            'user'  => $this->user,
        );
        return new TemplateResponse('dashboard', 'main', $params);
    }
}