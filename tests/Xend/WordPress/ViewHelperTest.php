<?php

namespace Xend\WordPress;


class ViewHelperTest extends \Xend\WordPress\TestCase {

    /**
     * @var \Xend\WordPress\ViewHelper
     */
    protected $fixture;

    public function setUp() {
        $this->fixture = new \Xend\WordPress\ViewHelper();
    }

    public function testGetBlogInfo() {
        $this->wordpress()->expects('get_bloginfo', $this->once())->with("name", "raw")->willReturn("My Test Blog");
        $this->assertEquals("My Test Blog", $this->fixture->getBlogInfo());
    }

    public function testGetBlogInfo2() {
        $this->wordpress()->expects('get_bloginfo', $this->once())->with("url", "raw")->willReturn("http://myblog.com");
        $this->assertEquals("http://myblog.com", $this->fixture->getBlogInfo("url"));
    }

    public function testGetBlogInfo3() {
        $this->wordpress()->expects('get_bloginfo', $this->once())->with("language", "display")->willReturn("en_us");
        $this->assertEquals("en_us", $this->fixture->getBlogInfo("language", "display"));
    }

    public function testBlogInfo() {
        $this->wordpress()->expects('get_bloginfo', $this->once())->with("name", "display")->willReturn("My Test Blog");
        ob_start();
        $this->fixture->blogInfo();
        $this->assertEquals("My Test Blog", ob_get_clean());
    }

    public function testBlogInfo2() {
        $this->wordpress()->expects('get_bloginfo', $this->once())
                          ->with("description", "display")
                          ->willreturn("My Test Blog is a fantastic weblog");
        ob_start();
        $this->fixture->blogInfo("description");
        $this->assertEquals("My Test Blog is a fantastic weblog", ob_get_clean());
    }

    public function testTitle() {
        $this->wordpress()->expects('wp_title', $this->once())
                          ->with('&raquo;', true, 'LEFT')
                          ->willReturn("My Test Blog &raquo; Home");
        ob_start();
        $this->fixture->title();
        $this->assertEquals("My Test Blog &raquo; Home", ob_get_clean());
    }

    public function testTitle2() {
        $this->wordpress()->expects('wp_title', $this->once())
             ->with('|', false, 'RIGHT')
             ->willReturn("My Test Blog | Home");
        $this->assertEquals("My Test Blog | Home", $this->fixture->title('|', true, 'RIGHT'));
    }

    public function testGetThemeDirectory() {
        $this->wordpress()->expects('get_template_directory', $this->once())
                          ->with()
                          ->willReturn("/path/to/parent/theme");
        $this->assertEquals("/path/to/parent/theme", $this->fixture->getThemeDirectory());
    }

    public function testGetThemeUri() {
        $this->wordpress()->expects('get_template_uri', $this->once())
             ->with()
             ->willReturn("/wp-content/themes/theme-dir");
        $this->assertEquals("/wp-content/themes/theme-dir", $this->fixture->getThemeUri());
    }

    public function testGetChildThemeDirectory() {
        $this->wordpress()->expects('get_stylesheet_directory', $this->once())
             ->with()
             ->willReturn("/path/to/child/theme");
        $this->assertEquals("/path/to/child/theme", $this->fixture->getChildThemeDirectory());
    }

    public function testGetChildThemeUri() {
        $this->wordpress()->expects('get_stylesheet_uri', $this->once())
             ->with()
             ->willReturn("/wp-content/themes/child-dir");
        $this->assertEquals("/wp-content/themes/child-dir", $this->fixture->getChildThemeUri());
    }

    public function testRegisterStyle() {
        $this->wordpress()->expects('wp_register_style', $this->once())
                          ->with('my-style', '/path/to/style.css', array(), false, 'all');
        $this->assertSame($this->fixture, $this->fixture->registerStyle('my-style', '/path/to/style.css'));
    }

    public function testRegisterStyle2() {
        $this->wordpress()->expects('wp_register_style', $this->once())
             ->with('my-style', '/path/to/style.css', array('bootstrap'), '1.0.0', 'screen');
        $this->assertSame($this->fixture,
                          $this->fixture->registerStyle('my-style', '/path/to/style.css', array('bootstrap'), '1.0.0',
                                                        'screen'));
    }

    public function testEnqueueStyle() {
        $this->wordpress()->expects('wp_enqueue_style', $this->once())
                          ->with('my-style', '', array(), false, 'all');
        $this->assertSame($this->fixture, $this->fixture->enqueueStyle('my-style'));
    }

    public function testEnqueueStyle2() {
        $this->wordpress()->expects('wp_enqueue_style', $this->once())
             ->with('my-style', '/alternate/uri/style.css', array('bootstrap'), '1.0.0', 'screen');
        $this->assertSame($this->fixture,
                          $this->fixture->enqueueStyle('my-style', '/alternate/uri/style.css', array('bootstrap'),
                                                       '1.0.0', 'screen'));
    }

    public function testPrintStyles() {
        $this->wordpress()->expects('wp_print_styles', $this->once())
                          ->with()
                          ->willReturnCallback(function() { echo "hello styles!"; });
        ob_start();
        $this->fixture->printStyles();
        $this->assertEquals('hello styles!', ob_get_clean());
    }

    public function testRegisterScript() {
        $this->wordpress()->expects('wp_register_script', $this->once())
             ->with('my-script', '/path/to/script.js', array(), false, false);
        $this->assertSame($this->fixture, $this->fixture->registerScript('my-script', '/path/to/script.js'));
    }

    public function testRegisterScript2() {
        $this->wordpress()->expects('wp_register_script', $this->once())
             ->with('my-script', '/path/to/script.js', array('bootstrap'), '1.0.0', true);
        $this->assertSame($this->fixture,
                          $this->fixture->registerScript('my-script', '/path/to/script.js', array('bootstrap'), '1.0.0',
                                                        true));
    }

    public function testEnqueueScript() {
        $this->wordpress()->expects('wp_enqueue_script', $this->once())
             ->with('my-script', '', array(), false, false);
        $this->assertSame($this->fixture, $this->fixture->enqueueScript('my-script'));
    }

    public function testEnqueueScript2() {
        $this->wordpress()->expects('wp_enqueue_script', $this->once())
             ->with('my-script', '/alternate/uri/script.js', array('bootstrap'), '1.0.0', true);
        $this->assertSame($this->fixture,
                          $this->fixture->enqueueScript('my-script', '/alternate/uri/script.js', array('bootstrap'),
                                                       '1.0.0', true));
    }

    public function testPrintScripts() {
        $this->wordpress()->expects('wp_print_scripts', $this->once())
             ->with()
             ->willReturnCallback(function() { echo "hello scripts!"; });
        ob_start();
        $this->fixture->printScripts();
        $this->assertEquals('hello scripts!', ob_get_clean());
    }

    public function testHeadAction() {
        $this->wordpress()->expects('wp_head', $this->once())->with();
        $this->fixture->headAction();
    }

    public function testFooterAction() {
        $this->wordpress()->expects('wp_footer', $this->once())->with();
        $this->fixture->footerAction();
    }

    public function testGetBodyClass() {
        $this->wordpress()->expects('get_body_class', $this->once())->with('')->willReturn(array('one', 'two'));
        $this->assertEquals('one two', $this->fixture->getBodyClass());
    }

    public function testGetBodyClass2() {
        $this->wordpress()->expects('get_body_class', $this->once())
                          ->with('three')
                          ->willReturn(array('one', 'two', 'three'));
        $this->assertEquals('one two three', $this->fixture->getBodyClass('three'));
    }

    public function testGetPostClass() {
        $post = new \Xend\WordPress\Posts\Post(new \WP_Post(new \ArrayObject(array('ID' => 1))));
        $this->wordpress()->expects('get_post_class', $this->once())->with('', 1)->willReturn(array('post', 'post-1'));
        $this->assertEquals('post post-1', $this->fixture->getPostClass($post));
    }

    public function testGetPostClass2() {
        $post = new \Xend\WordPress\Posts\Post(new \WP_Post(new \ArrayObject(array('ID' => 1))));
        $this->wordpress()->expects('get_post_class', $this->once())
                          ->with('my-post', 1)
                          ->willReturn(array('post', 'post-1', 'my-post'));
        $this->assertEquals('post post-1 my-post', $this->fixture->getPostClass($post, 'my-post'));
    }

    public function testGetCommentClass() {
        $comment = new \Xend\WordPress\Posts\Comment(
            new \ArrayObject(array('comment_ID' => 2, 'comment_post_ID' => 1), \ArrayObject::ARRAY_AS_PROPS));
        $this->wordpress()->expects('get_comment_class', $this->once())
                          ->with('', 2, 1)
                          ->willReturn(array('comment', 'comment-2'));
        $this->assertEquals('comment comment-2', $this->fixture->getCommentClass($comment));
    }

    public function testGetCommentClass2() {
        $comment = new \Xend\WordPress\Posts\Comment(
            new \ArrayObject(array('comment_ID' => 2, 'comment_post_ID' => 1), \ArrayObject::ARRAY_AS_PROPS));
        $this->wordpress()->expects('get_comment_class', $this->once())
             ->with('my-comment', 2, 1)
             ->willReturn(array('comment', 'comment-2', 'my-comment'));
        $this->assertEquals('comment comment-2 my-comment', $this->fixture->getCommentClass($comment, 'my-comment'));
    }

    public function testRenderMenu() {
        $menu = new \Xend\WordPress\ViewHelper\Menu("My Menu");
        $this->wordpress()->expects('wp_nav_menu', $this->once())
                          ->with(array('theme_location' => 'My Menu', 'container' => 'div', 'echo' => false))
                          ->willReturn("My Menu Goes Here");
        $this->assertEquals("My Menu Goes Here", $this->fixture->renderMenu($menu, true));
    }

    public function testRenderMenu2() {
        $menu = new \Xend\WordPress\ViewHelper\Menu("my-menu", array('maxDepth' => 3),
                                                    \Xend\WordPress\ViewHelper\Menu::REF_BY_OTHER);
        $menu->container = "nav";
        $menu->containerId = "my-nav-menu";
        $menu->containerClass = "nav-menu";
        $menu->menuId = "nav-items";
        $menu->menuClass = "nav-list";
        $menu->beforeLink = "Click this: ";
        $menu->afterLink = " :click That";
        $menu->beforeLinkText = "(";
        $menu->afterLinkText = ")";
        $menu->renderPattern = '<ul class="%2$s" id="%1$s">%3$s</ul>';
        $menu->fallback = "This Menu Is Missing!";

        $expected = <<< EOT
<nav id="my-nav-mnu" class="nav-menu">
    <ul class="nav-list" id="nav-items">
        <li>Click this: <a href="/my-link.html" title="My Link">(My Link)</a> :click That</li>
    </ul>
</nav>
EOT;

        $this->wordpress()->expects('wp_nav_menu', $this->once())
                          ->with(array(
                                     'menu' => 'my-menu',
                                     'container' => 'nav',
                                     'container_id' => 'my-nav-menu',
                                     'container_class' => 'nav-menu',
                                     'menu_id' => 'nav-items',
                                     'menu_class' => 'nav-list',
                                     'items_wrap' => '<ul class="%2$s" id="%1$s">%3$s</ul>',
                                     'before' => 'Click this: ',
                                     'after' => ' :click That',
                                     'link_before' => '(',
                                     'link_after' => ')',
                                     'depth' => 3
                                 ))
                          ->willReturnCallback(function() use ($expected) { echo $expected; });

        ob_start();
        $this->fixture->renderMenu($menu);
        $this->assertEquals($expected, ob_get_clean());
    }

    public function testRenderSidebar() {
        $this->wordpress()->expects('dynamic_sidebar', $this->once())
                          ->with("my-sidebar")
                          ->willReturnCallback(function () { echo "My Sidebar Goes Here"; return true;});
        $this->assertEquals("My Sidebar Goes Here", $this->fixture->renderSidebar("my-sidebar", true));
    }

    public function testRenderSidebar2() {
        $this->wordpress()->expects('dynamic_sidebar', $this->once())
                          ->with("my-sidebar")
                          ->willReturn(false);
        $this->assertFalse($this->fixture->renderSidebar("my-sidebar", true));
    }

    public function testRenderSidebar3() {
        $this->wordpress()->expects('dynamic_sidebar', $this->once())
                          ->with('my-sidebar')
                          ->willReturn(true);
        $this->assertTrue($this->fixture->renderSidebar('my-sidebar'));
    }

    public function testRenderCommentForm() {
        $this->wordpress()->expects('comment_form', $this->once())
                          ->with(array(), null)
                          ->willReturnCallback(function() { echo "Comment Form"; });
        ob_start();
        $this->fixture->renderCommentForm();
        $this->assertEquals("Comment Form", ob_get_clean());
    }

    public function testRenderCommentForm2() {

        $expectedOptions = array(
            'fields'               => array('author' => '<input name="author" />'),
            'comment_field'        => '<textarea name="comment"></textarea>',
            'must_log_in'          => "You must log in",
            'logged_in_as'         => "You are logged in as you",
            'comment_notes_before' => "You can comment!",
            'comment_notes_after'  => "Did you comment?",
            'id_form'              => "comment-form",
            'id_submit'            => "submit-comment",
            'class_submit'         => "submit-comment-button",
            'name_submit'          => "submit-button",
            'label_submit'         => "Comment!",
            'title_reply'          => "Leave a Comment",
            'title_reply_to'       => "Reply to %s",
            'cancel_reply_link'    => "Nevermind",
            'format'               => 'html5'
        );

        $commentForm = new \Xend\WordPress\ViewHelper\CommentForm(array('format' => $expectedOptions['format']));
        $commentForm->title = $expectedOptions['title_reply'];
        $commentForm->replyTitle = $expectedOptions['title_reply_to'];
        $commentForm->fields = $expectedOptions['fields'];
        $commentForm->commentField = $expectedOptions['comment_field'];
        $commentForm->mustLogInMessage = $expectedOptions['must_log_in'];
        $commentForm->loggedInAsMessage = $expectedOptions['logged_in_as'];
        $commentForm->beforeMessage = $expectedOptions['comment_notes_before'];
        $commentForm->afterMessage = $expectedOptions['comment_notes_after'];
        $commentForm->formId = $expectedOptions['id_form'];
        $commentForm->submitId = $expectedOptions['id_submit'];
        $commentForm->submitClass = $expectedOptions['class_submit'];
        $commentForm->submitName = $expectedOptions['name_submit'];
        $commentForm->submitText = $expectedOptions['label_submit'];
        $commentForm->cancelText = $expectedOptions['cancel_reply_link'];

        $this->wordpress()->expects('comment_form', $this->once())
                          ->with($expectedOptions, 2)
                          ->willReturnCallback(function() {echo "Comment Form!";});
        ob_start();
        $this->fixture->renderCommentForm($commentForm, 2);
        $this->assertEquals("Comment Form!", ob_get_clean());
    }
}
