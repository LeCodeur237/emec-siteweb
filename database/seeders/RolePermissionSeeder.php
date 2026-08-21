<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'Manage users', 'slug' => 'users.manage', 'description' => 'Create, update and disable admin users.'],
            ['name' => 'View users', 'slug' => 'users.view', 'description' => 'View admin users.'],
            ['name' => 'Create users', 'slug' => 'users.create', 'description' => 'Create admin users.'],
            ['name' => 'Update users', 'slug' => 'users.update', 'description' => 'Update admin users.'],
            ['name' => 'Delete users', 'slug' => 'users.delete', 'description' => 'Delete admin users.'],
            ['name' => 'Manage roles', 'slug' => 'roles.manage', 'description' => 'Manage roles and permissions.'],
            ['name' => 'Manage churches', 'slug' => 'churches.manage', 'description' => 'Manage EMEC churches and leaders.'],
            ['name' => 'View churches', 'slug' => 'churches.view', 'description' => 'View churches in administration.'],
            ['name' => 'Create churches', 'slug' => 'churches.create', 'description' => 'Create churches and church leaders.'],
            ['name' => 'Update churches', 'slug' => 'churches.update', 'description' => 'Update churches and church leaders.'],
            ['name' => 'Delete churches', 'slug' => 'churches.delete', 'description' => 'Delete churches and church leaders.'],
            ['name' => 'Manage groups', 'slug' => 'groups.manage', 'description' => 'Manage EMEC groups and group leaders.'],
            ['name' => 'View groups', 'slug' => 'groups.view', 'description' => 'View groups in administration.'],
            ['name' => 'Create groups', 'slug' => 'groups.create', 'description' => 'Create groups and group leaders.'],
            ['name' => 'Update groups', 'slug' => 'groups.update', 'description' => 'Update groups and group leaders.'],
            ['name' => 'Delete groups', 'slug' => 'groups.delete', 'description' => 'Delete groups and group leaders.'],
            ['name' => 'Manage events', 'slug' => 'events.manage', 'description' => 'Manage events and weekly programs.'],
            ['name' => 'View events', 'slug' => 'events.view', 'description' => 'View events in administration.'],
            ['name' => 'Create events', 'slug' => 'events.create', 'description' => 'Create events.'],
            ['name' => 'Update events', 'slug' => 'events.update', 'description' => 'Update events.'],
            ['name' => 'Delete events', 'slug' => 'events.delete', 'description' => 'Delete events.'],
            ['name' => 'Manage messages', 'slug' => 'messages.manage', 'description' => 'Manage messages, preachers, categories and series.'],
            ['name' => 'View messages', 'slug' => 'messages.view', 'description' => 'View messages in administration.'],
            ['name' => 'Create messages', 'slug' => 'messages.create', 'description' => 'Create messages.'],
            ['name' => 'Update messages', 'slug' => 'messages.update', 'description' => 'Update messages.'],
            ['name' => 'Delete messages', 'slug' => 'messages.delete', 'description' => 'Delete messages.'],
            ['name' => 'Publish messages', 'slug' => 'messages.publish', 'description' => 'Publish and unpublish messages.'],
            ['name' => 'Manage DOSC', 'slug' => 'dosc.manage', 'description' => 'Manage social projects, actions, testimonials and impact stats.'],
            ['name' => 'View DOSC projects', 'slug' => 'dosc.projects.view', 'description' => 'View DOSC projects in administration.'],
            ['name' => 'Create DOSC projects', 'slug' => 'dosc.projects.create', 'description' => 'Create DOSC projects.'],
            ['name' => 'Update DOSC projects', 'slug' => 'dosc.projects.update', 'description' => 'Update DOSC projects.'],
            ['name' => 'Delete DOSC projects', 'slug' => 'dosc.projects.delete', 'description' => 'Delete DOSC projects.'],
            ['name' => 'View DOSC actions', 'slug' => 'dosc.actions.view', 'description' => 'View DOSC actions in administration.'],
            ['name' => 'Create DOSC actions', 'slug' => 'dosc.actions.create', 'description' => 'Create DOSC actions.'],
            ['name' => 'Update DOSC actions', 'slug' => 'dosc.actions.update', 'description' => 'Update DOSC actions.'],
            ['name' => 'Delete DOSC actions', 'slug' => 'dosc.actions.delete', 'description' => 'Delete DOSC actions.'],
            ['name' => 'Manage media', 'slug' => 'media.manage', 'description' => 'Manage uploaded media records.'],
            ['name' => 'View media', 'slug' => 'media.view', 'description' => 'View media library.'],
            ['name' => 'Upload media', 'slug' => 'media.upload', 'description' => 'Upload media.'],
            ['name' => 'Update media', 'slug' => 'media.update', 'description' => 'Update media metadata.'],
            ['name' => 'Delete media', 'slug' => 'media.delete', 'description' => 'Delete media.'],
            ['name' => 'Manage donations', 'slug' => 'donations.manage', 'description' => 'Manage donation campaigns, methods and donation records.'],
            ['name' => 'View donations', 'slug' => 'donations.view', 'description' => 'View donation records.'],
            ['name' => 'Manage communication', 'slug' => 'communication.manage', 'description' => 'Manage contact messages and newsletter subscribers.'],
            ['name' => 'View notifications', 'slug' => 'notifications.view', 'description' => 'View administrative notifications.'],
            ['name' => 'Manage settings', 'slug' => 'settings.manage', 'description' => 'Manage site settings.'],
        ])->mapWithKeys(function (array $permission) {
            $model = Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );

            return [$model->slug => $model];
        });

        $roles = [
            'super_admin' => [
                'name' => 'Super Admin',
                'description' => 'Full access to all backend modules.',
                'permissions' => $permissions->keys()->all(),
            ],
            'admin' => [
                'name' => 'Admin',
                'description' => 'General administration access.',
                'permissions' => [
                    'users.view',
                    'users.create',
                    'users.update',
                    'users.delete',
                    'churches.manage',
                    'churches.view',
                    'churches.create',
                    'churches.update',
                    'churches.delete',
                    'groups.manage',
                    'groups.view',
                    'groups.create',
                    'groups.update',
                    'groups.delete',
                    'events.manage',
                    'events.view',
                    'events.create',
                    'events.update',
                    'events.delete',
                    'messages.manage',
                    'messages.view',
                    'messages.create',
                    'messages.update',
                    'messages.delete',
                    'messages.publish',
                    'dosc.manage',
                    'dosc.projects.view',
                    'dosc.projects.create',
                    'dosc.projects.update',
                    'dosc.projects.delete',
                    'dosc.actions.view',
                    'dosc.actions.create',
                    'dosc.actions.update',
                    'dosc.actions.delete',
                    'media.manage',
                    'media.view',
                    'media.upload',
                    'media.update',
                    'media.delete',
                    'communication.manage',
                    'notifications.view',
                    'settings.manage',
                    'donations.view',
                    'donations.manage',
                    'notifications.view',
                ],
            ],
            'editor' => [
                'name' => 'Editor',
                'description' => 'Content editor across public website modules.',
                'permissions' => [
                    'churches.manage',
                    'churches.view',
                    'churches.create',
                    'churches.update',
                    'churches.delete',
                    'groups.manage',
                    'groups.view',
                    'groups.create',
                    'groups.update',
                    'groups.delete',
                    'events.manage',
                    'events.view',
                    'events.create',
                    'events.update',
                    'events.delete',
                    'messages.manage',
                    'messages.view',
                    'messages.create',
                    'messages.update',
                    'messages.delete',
                    'messages.publish',
                    'media.manage',
                    'media.view',
                    'media.upload',
                    'media.update',
                ],
            ],
            'messages_editor' => [
                'name' => 'Messages Editor',
                'description' => 'Messages website content editor.',
                'permissions' => [
                    'messages.manage',
                    'messages.view',
                    'messages.create',
                    'messages.update',
                    'messages.delete',
                    'messages.publish',
                    'media.manage',
                    'media.view',
                ],
            ],
            'dosc_editor' => [
                'name' => 'DOSC Editor',
                'description' => 'DOSC content editor.',
                'permissions' => [
                    'dosc.manage',
                    'dosc.projects.view',
                    'dosc.projects.create',
                    'dosc.projects.update',
                    'dosc.projects.delete',
                    'dosc.actions.view',
                    'dosc.actions.create',
                    'dosc.actions.update',
                    'dosc.actions.delete',
                    'media.manage',
                    'media.view',
                ],
            ],
            'media_manager' => [
                'name' => 'Media Manager',
                'description' => 'Media library manager.',
                'permissions' => [
                    'media.manage',
                    'media.view',
                    'media.upload',
                    'media.update',
                    'media.delete',
                ],
            ],
            'finance_manager' => [
                'name' => 'Finance Manager',
                'description' => 'Donation records manager.',
                'permissions' => [
                    'donations.view',
                    'donations.manage',
                ],
            ],
        ];

        foreach ($roles as $slug => $roleData) {
            $role = Role::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $roleData['name'],
                    'description' => $roleData['description'],
                ]
            );

            $role->permissions()->sync(
                $permissions->only($roleData['permissions'])->pluck('id')->all()
            );
        }
    }
}
