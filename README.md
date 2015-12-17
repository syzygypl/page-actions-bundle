# Page subactions

This bundle enables to mount controllers (or any other routing for that matter) under any [Kunstmaan CMS][kunstmaan] page.
In effect, given a `/foo/bar` page and a `/thank-you` route, you can access `/foo/bar/thank-you` and have `$page` and
`$nodeTranslation` of `/foo/bar` provided.

## Installation

1. `composer require arsthanea/page-actions-bundle`
2. Add `PageActionsBundle` to your Kernel
3. Update your db schema / create migration 
4. Import the `page_actions` routing in your main `routing.yml`:
 
```yaml
# app/config/routing.yml

_page_actions:
    type: page_actions
    resource: .
    
```
    
## Usage 

### Configure available resources

First, you need to configure available routes / controllers. Since this routing is highly dynamic, not any route will
match any page, so we need to have some hints. List your resources in configuration:

```yaml
# app/config/config.yml or such

page_actions:
    resources:
        landing_page: 
            resource: @LandingPageBundle/Controller/LandingPageActionsController.php
            type: annotation
```

This is either an `annotation` and a controller, or a `yaml` / `xml` etc with routes, same as you’d use in `routing.yml`.
You cannot however use other configuration options such as `prefix`, `defaults`, etc. The key name is important, it will
be used later.

### Create controller

Let’s now define the controller. You can automatically use `$page` and `$nodeTranslation` in your actions. Besides
that, it’s just a standard controller. For example:

```php
class LandingPageActionsController extends Controller {

    /**
     * @Route(name="landing_page_submit", path="submit", methods = {"POST"})
     *
     * @param Request         $request
     * @param NodeTranslation $nodeTranslation
     *
     * @return RedirectResponse
     */
    public function submitAction(Request $request, NodeTranslation $nodeTranslation) {
       // 
       // handle some form data
       //
       return $this->redirectToRoute('landing_page_thank_you', ["url" => $nodeTranslation->getUrl()]);
    }
    
    /**
     * @Route(name="landing_page_thank_you", path="thank-you")
     *
     * @param HasNodeInterface $page
     *
     * @return Response
     */
    public function submitAction(HasNodeInterface $page) {
       // 
       // notice that $page is referencing to current page
       // 
       return $this->render('@LandingPageBundle/Pages/ThankYou.html.twig, ["page" => $page]);
    }
    
}
```

### Configure the page

The last piece is configuring the page entity to handle specified actions. Do this by implementing the `PageActionsInterface`:

```php

# Entity\LandingPage.php

class LandingPage extends AbstractEntity implements PageActionsInterface {

// …
    public function getPageActions() {
        return ['landing_page']; 
    }
// …

}
```

Notice, that the values returned by this method need to match the keys defined in configuration earlier.

### Save the page

Nothing works yet. Now you need to save the page, for the custom page routes to be created.

### Use the actions

For example, in your default view:

```twig
<form action={{ path("landing_page_submit", { "url": nodetranslation.url }) }}" method="POST">
```

You didn’t specify the `url` parameter in your route, but since the resources are mounted relative to a page it
is added automatically by the bundle and you need to provide it when generating routes.


  [kunstmaan]: https://bundles.kunstmaan.be
