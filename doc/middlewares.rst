Middleware
==========

PrestoPHP allows you to run code, that changes the default PrestoPHP behavior, at
different stages during the handling of a request through *middleware*:

* *Application middleware* is triggered independently of the current handled
  request;

* *Route middleware* is triggered when its associated route is matched.

Application Middleware
----------------------

Application middleware is only run for the "master" Request.

Before Middleware
~~~~~~~~~~~~~~~~~

A *before* application middleware allows you to tweak the Request before the
controller is executed::

    $app->before(function (Request $request, Application $app) {
        // ...
    });

By default, the middleware is run after the routing and the security.

If you want your middleware to be run even if an exception is thrown early on
(on a 404 or 403 error for instance), then, you need to register it as an
early event::

    $app->before(function (Request $request, Application $app) {
        // ...
    }, Application::EARLY_EVENT);

In this case, the routing and the security won't have been executed, and so you
won't have access to the locale, the current route, or the security user.

.. note::

    The before middleware is an event registered on the Symfony *request*
    event.

After Middleware
~~~~~~~~~~~~~~~~

An *after* application middleware allows you to tweak the Response before it
is sent to the client::

    $app->after(function (Request $request, Response $response) {
        // ...
    });

.. note::

    The after middleware is an event registered on the Symfony *response*
    event.

Finish Middleware
~~~~~~~~~~~~~~~~~

A *finish* application middleware allows you to execute tasks after the
Response has been sent to the client (like sending emails or logging)::

    $app->finish(function (Request $request, Response $response) {
        // ...
        // Warning: modifications to the Request or Response will be ignored
    });

.. note::

    The finish middleware is an event registered on the Symfony *terminate*
    event.

Route Middleware
----------------

Route middleware is added to routes or route collections and it is only
triggered when the corresponding route is matched. You can also stack them::

    $app->get('/somewhere', function () {
        // ...
    })
    ->before($before1)
    ->before($before2)
    ->after($after1)
    ->after($after2)
    ;

Before Middleware
~~~~~~~~~~~~~~~~~

A *before* route middleware is fired just before the route callback, but after
the *before* application middleware::

    $before = function (Request $request, Application $app) {
        // ...
    };

    $app->get('/somewhere', function () {
        // ...
    })
    ->before($before);

After Middleware
~~~~~~~~~~~~~~~~

An *after* route middleware is fired just after the route callback, but before
the application *after* application middleware::

    $after = function (Request $request, Response $response, Application $app) {
        // ...
    };

    $app->get('/somewhere', function () {
        // ...
    })
    ->after($after);

Middleware Priority
-------------------

You can add as much middleware as you want, in which case they are triggered
in the same order as you added them.

You can explicitly control the priority of your middleware by passing an
additional argument to the registration methods::

    $app->before(function (Request $request) {
        // ...
    }, 32);

As a convenience, two constants allow you to register an event as early as
possible or as late as possible::

    $app->before(function (Request $request) {
        // ...
    }, Application::EARLY_EVENT);

    $app->before(function (Request $request) {
        // ...
    }, Application::LATE_EVENT);

Short-circuiting the Controller
-------------------------------

If a *before* middleware returns a ``Response`` object, the request handling is
short-circuited (the next middleware won't be run, nor the route
callback), and the Response is passed to the *after* middleware right away::

    $app->before(function (Request $request) {
        // redirect the user to the login screen if access to the Resource is protected
        if (...) {
            return new RedirectResponse('/login');
        }
    });

.. note::

    A ``RuntimeException`` is thrown if a before middleware does not return a
    Response or ``null``.
