QuizzBox Affaires
=================
Description and features
------------------------

This application is design for follow and treat commercial works. It receives the business from an Exchange Mail address, store it in a DataBase, and all the commercials can deal with all business. The fonctionnality for the business are:

  * Follow it with a task management (call, demo, proposal, reminder, sign in progress, signed, break, ended)
  * Add some comment for every task
  * Order business (default: by reminder date)
  * Collection of filter (by date, by commercial, by business' state, with a searchbox)
  * Auto Refresh, not disturbing work in progress

To set up your db, just use Doctrine usual commands:

```shell
C:\my\path>php bin/console doctrine:database:create
C:\my\path>php bin/console doctrine:schema:update --force
```
In addition to this creation, you may update db from mailbox with this command:
```shell
C:\my\path>php bin/console update:db
```
To be completely efficient, if you are working on a Unix server system, you can create a CRON task, which performs this command periodically.


Users and security
------------------

This application uses FOSUserBundle. It provide users management with different roles:
  - SUPER ADMIN
  - ADMIN
  - USER
  - GUEST

SUPER_ADMIN and ADMIN are allowed to manage all users (create, promote, demote...). They can also manage all commercials. ADMIN and SUPER_ADMIN can have a quick view of the INBOX mail. They obviously have the same privilege as the USERS.

USERS manage all the business, grant access to all task and business, creat, modify, delete all of them.

GUEST are forbidden to all access. The only task they can do is to login.


What's inside?
--------------

The Symfony Standard Edition is configured with the following defaults:

  * An AppBundle you can use to start coding

  * Twig as the only configured template engine

  * Doctrine ORM/DBAL

  * Swiftmailer

  * Annotations enabled for everything

It comes with the following bundles:

  * [**Symfony**][1] - Symfony is a PHP web application framework and a set of reusable PHP components/libraries.

  * [**FOSUserBundle**][2] - Symfony bundle for security, that allows you to load users from configuration, a database, or anywhere else.panneau

  * [**PHP Exchange Web Services**][3] - Symfony bundle that allows you to communicate with Microsogestionft Exchange servers using Exchange Web Service easier.

And some other plug-ins et tools:

  * [**DataTables**][4] - A jQuery plug-in for advanced interaction controls for HTML tables 

  * [**phpMyAdmin**][5] - Software tool for intended to handle the administration of MySQL over the Web.



[1]:  http://symfony.com/doc/current/index.html#gsc.tab=0
[2]:  https://symfony.com/doc/current/bundles/FOSUserBundle/index.html
[3]:  https://github.com/jamesiarmes/php-ews
[4]:  https://datatables.net/
[5]:  https://www.phpmyadmin.net/