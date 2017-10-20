<?php

namespace Chaplean\Bundle\RedmineClientBundle\Api;

use Chaplean\Bundle\RestClientBundle\Api\AbstractApi;
use Chaplean\Bundle\RestClientBundle\Api\Parameter;
use Chaplean\Bundle\RestClientBundle\Api\RequestRoute;
use Chaplean\Bundle\RestClientBundle\Api\Route;
use GuzzleHttp\ClientInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class RedmineApi
 *
 * @method Route        getProjects()
 * @method Route        getProject()
 * @method Route        getUsers()
 * @method Route        getUser()
 * @method Route        getIssues()
 * @method Route        getIssue()
 * @method Route        getTimes()
 * @method Route        getTime()
 *
 * @method RequestRoute postProjects()
 * @method RequestRoute postUsers()
 * @method RequestRoute postIssues()
 * @method RequestRoute postTimes()
 *
 * @method RequestRoute putProjects()
 * @method RequestRoute putUsers()
 * @method RequestRoute putIssues()
 * @method RequestRoute putTimes()
 *
 * @method Route        deleteProjects()
 * @method Route        deleteUsers()
 * @method Route        deleteIssues()
 * @method Route        deleteTimes()
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2017 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 */
class RedmineApi extends AbstractApi
{
    protected $token;

    protected $url;

    /**
     * RedmineApi constructor.
     *
     * @param ClientInterface          $caller
     * @param EventDispatcherInterface $eventDispatcher
     * @param string                   $url
     * @param string|null              $accessToken
     */
    public function __construct(ClientInterface $caller, EventDispatcherInterface $eventDispatcher, $url, $accessToken = null)
    {
        $this->token = $accessToken;
        $this->url = $url;
        parent::__construct($caller, $eventDispatcher);
    }

    /**
     * @param string $token
     *
     * @return void
     */
    public function setToken($token)
    {
        $this->token = $token;
        $this->routes = [];
        parent::__construct($this->client, $this->eventDispatcher);
    }

    /**
     * Set api actions.
     *
     * @return void
     */
    public function buildApi()
    {
        $this->globalParameters()
            ->sendJson()
            ->urlPrefix($this->url)
            ->queryParameters(
                [
                    'key' => Parameter::string()
                        ->defaultValue($this->token),
                ]
            );

        $this->get('projects', 'projects.json')
            ->queryParameters(
                [
                    'key'     => Parameter::string()
                        ->defaultValue($this->token),
                    'include' => Parameter::arrayList(Parameter::string()/*->values(['trackers', 'issue_categories', 'enabled_modules'])*/)
                        ->optional()
                ]
            );

        $this->get('project', 'projects/{id}.json')
            ->urlParameters(
                [
                    'id' => Parameter::id()
                ]
            )
            ->queryParameters(
                [
                    'key'     => Parameter::string()
                        ->defaultValue($this->token),
                    'include' => Parameter::arrayList(Parameter::string()/*->values(['trackers', 'issue_categories', 'enabled_modules'])*/)
                        ->optional()
                ]
            );

        /**
         * status:
         *  1: Active
         *  2: Registered
         *  3: Locked
         */
        $this->get('users', 'users.json')
            ->queryParameters(
                [
                    'key'      => Parameter::string()
                        ->defaultValue($this->token),
                    'status'   => Parameter::arrayList(Parameter::int()/*->values(['1', '2', '3'])*/)
                        ->optional(),
                    'name'     => Parameter::string()
                        ->optional(),
                    'group_id' => Parameter::id()
                        ->optional(),
                ]
            );

        $this->get('user', 'users/{id}.json')
            ->urlParameters(
                [
                    'id' => Parameter::id()
                ]
            )
            ->queryParameters(
                [
                    'key'     => Parameter::string()
                        ->defaultValue($this->token),
                    'include' => Parameter::arrayList(Parameter::string()/*->values(['groups', 'memberships'])*/)
                        ->optional()
                ]
            );

        $this->get('issues', 'issues.json')
            ->queryParameters(
                [
                    'key'            => Parameter::string()
                        ->defaultValue($this->token),
                    'sort'           => Parameter::string()
                        ->optional(),
                    'offset'         => Parameter::int()
                        ->optional(),
                    'limit'          => Parameter::int()
                        ->optional(),
                    'project_id'     => Parameter::id()
                        ->optional(),
                    'subproject_id'  => Parameter::id()
                        ->optional(),
                    'tracker_id'     => Parameter::id()
                        ->optional(),
                    'status_id'      => Parameter::string()
                        ->optional(),
                    'assigned_to_id' => Parameter::id()
                        ->optional(),
                    'cf_x'           => Parameter::string()
                        ->optional(),
                    'cf_12'          => Parameter::string()
                        ->optional(),
                    'cf_26'          => Parameter::string()
                        ->optional(),
                    'cf_27'          => Parameter::string()
                        ->optional(),
                ]
            );

        $this->get('issue', 'issues/{id}.json')
            ->urlParameters(
                [
                    'id' => Parameter::id()
                ]
            )
            ->queryParameters(
                [
                    'key'     => Parameter::string()
                        ->defaultValue($this->token),
                    'include' => Parameter::arrayList(Parameter::string()/*->values(['children', 'attachments', 'relations', 'changesets', 'journals', 'watchers'])*/)
                        ->optional()
                ]
            );

        $this->get('times', 'time_entries.json')
            ->queryParameters(
                [
                    'key'      => Parameter::string()
                        ->defaultValue($this->token),
                    'issue_id' => Parameter::id()
                        ->optional(), // not in the actual documentation
                    'user_id'  => Parameter::id()
                        ->optional(), // not in the actual documentation
                ]
            );

        $this->get('time', 'time_entries/{id}.json')
            ->urlParameters(
                [
                    'id' => Parameter::id()
                ]
            );

        $this->delete('users', 'users/{id}.json')
            ->urlParameters(
                [
                    'id' => Parameter::id()
                ]
            );

        $this->delete('projects', 'projects/{id}.json')
            ->urlParameters(
                [
                    'id' => Parameter::id()
                ]
            );

        $this->delete('issues', 'issues/{id}.json')
            ->urlParameters(
                [
                    'id' => Parameter::id()
                ]
            );

        $this->delete('times', 'time_entries/{id}.json')
            ->urlParameters(
                [
                    'id' => Parameter::id()
                ]
            );

        $this->post('projects', 'projects.json')
            ->requestParameters(
                [
                    'project' => Parameter::object(
                        [
                            'name'                 => Parameter::string(),
                            'identifier'           => Parameter::string(),
                            'description'          => Parameter::string()
                                ->optional(),
                            'homepage'             => Parameter::string()
                                ->optional(),
                            'is_public'            => Parameter::bool()
                                ->optional(),
                            'parent_id'            => Parameter::id()
                                ->optional(),
                            'inherit_members'      => Parameter::bool()
                                ->optional(),
                            'tracker_ids'          => Parameter::arrayList(Parameter::id())
                                ->optional(),
                            'enabled_module_names' => Parameter::arrayList(
                                Parameter::string(
                                )/*->values(['boards', 'calendar', 'documents', 'files', 'gantt', 'issue_tracking', 'news', 'repository', 'time_tracking', 'wiki' ])*/
                            )
                                ->optional(),
                        ]
                    )
                ]
            );

        $this->post('users', 'users.json')
            ->requestParameters(
                [
                    'user' => Parameter::object(
                        [
                            'login'              => Parameter::string(),
                            'password'           => Parameter::string(),
                            'firstname'          => Parameter::string()
                                ->optional(),
                            'lastname'           => Parameter::string(),
                            'mail'               => Parameter::string(),
                            'auth_source_id'     => Parameter::string()
                                ->optional(),
                            'mail_notification'  => Parameter::string()
                                ->optional(),
                            'must_change_passwd' => Parameter::bool(),
                        ]
                    )
                ]
            );

        $this->post('issues', 'issues.json')
            ->requestParameters(
                [
                    'issue' => Parameter::object(
                        [
                            'project_id'       => Parameter::id()
                                ->optional(),
                            'tracker_id'       => Parameter::id()
                                ->optional(),
                            'status_id'        => Parameter::id()
                                ->optional(),
                            'priority_id'      => Parameter::id()
                                ->optional(),
                            'subject'          => Parameter::string()
                                ->optional(),
                            'description'      => Parameter::string()
                                ->optional(),
                            'category_id'      => Parameter::id()
                                ->optional(),
                            'fixed_version_id' => Parameter::id()
                                ->optional(),
                            'assigned_to_id'   => Parameter::id()
                                ->optional(),
                            'parent_issue_id'  => Parameter::id()
                                ->optional(),
                            'custom_fields'    => Parameter::arrayList(Parameter::object(
                                [
                                    'id' => Parameter::id(),
                                    'value' => Parameter::untyped()
                                ]
                            ))->optional(),
                            'watcher_user_ids' => Parameter::arrayList(Parameter::id())
                                ->optional(),
                            'is_private'       => Parameter::bool()
                                ->optional(),
                            'estimated_hours'  => Parameter::float()
                                ->optional(),
                        ]
                    )
                ]
            );

        $this->post('times', 'time_entries.json')
            ->requestParameters(
                [
                    'time_entry' => Parameter::object(
                        [
                            'issue_id'    => Parameter::id()
                                ->optional(),
                            'project_id'  => Parameter::id()
                                ->optional(),
                            'spent_on'    => Parameter::dateTime()
                                ->optional(),
                            'hours'       => Parameter::float(),
                            'activity_id' => Parameter::id()
                                ->optional(),
                            'comments'    => Parameter::string()
                                ->optional(),
                        ]
                    )
                        ->requireAtLeastOneOf(['issue_id', 'project_id'])
                ]
            );

        $this->put('projects', 'projects/{id}.json')
            ->urlParameters(
                [
                    'id' => Parameter::id()
                ]
            );

        $this->put('users', 'users/{id}.json')
            ->urlParameters(
                [
                    'id' => Parameter::id()
                ]
            )
            ->requestParameters(
                [
                    'user' => Parameter::object(
                        [
                            'login'              => Parameter::string(),
                            'password'           => Parameter::string(),
                            'firstname'          => Parameter::string()
                                ->optional(),
                            'lastname'           => Parameter::string(),
                            'mail'               => Parameter::string(),
                            'auth_source_id'     => Parameter::string()
                                ->optional(),
                            'mail_notification'  => Parameter::string()
                                ->optional(),
                            'must_change_passwd' => Parameter::bool(),
                        ]
                    )
                ]
            );

        $this->put('issues', 'issues/{id}.json')
            ->urlParameters(
                [
                    'id' => Parameter::id()
                ]
            )
            ->requestParameters(
                [
                    'issue' => Parameter::object(
                        [
                            'project_id'       => Parameter::id()
                                ->optional(),
                            'tracker_id'       => Parameter::id()
                                ->optional(),
                            'status_id'        => Parameter::id()
                                ->optional(),
                            'priority_id'      => Parameter::id()
                                ->optional(),
                            'subject'          => Parameter::string()
                                ->optional(),
                            'description'      => Parameter::string()
                                ->optional(),
                            'category_id'      => Parameter::id()
                                ->optional(),
                            'fixed_version_id' => Parameter::id()
                                ->optional(),
                            'assigned_to_id'   => Parameter::id()
                                ->optional(),
                            'parent_issue_id'  => Parameter::id()
                                ->optional(),
                            'custom_fields'    => Parameter::arrayList(Parameter::object(
                                [
                                    'id' => Parameter::id(),
                                    'value' => Parameter::untyped()
                                ]
                            ))->optional(),
                            'watcher_user_ids' => Parameter::arrayList(Parameter::id())
                                ->optional(),
                            'is_private'       => Parameter::bool()
                                ->optional(),
                            'estimated_hours'  => Parameter::float()
                                ->optional(),
                            'notes'            => Parameter::string()
                                ->optional(),
                            'private_notes'    => Parameter::bool()
                                ->optional(),
                        ]
                    )
                ]
            );

        $this->put('times', 'time_entries/{id}.json')
            ->urlParameters(
                [
                    'id' => Parameter::id()
                ]
            )
            ->requestParameters(
                [
                    'time_entry' => Parameter::object(
                        [
                            'issue_id'    => Parameter::id()
                                ->optional(),
                            'project_id'  => Parameter::id()
                                ->optional(),
                            'spent_on'    => Parameter::dateTime()
                                ->optional(),
                            'hours'       => Parameter::float(),
                            'activity_id' => Parameter::id()
                                ->optional(),
                            'comments'    => Parameter::string()
                                ->optional(),
                        ]
                    )
                        ->requireAtLeastOneOf(['issue_id', 'project_id'])
                ]
            );
    }
}
