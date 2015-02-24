<?php

namespace Xend\WordPress;


class PostsTest extends TestCase {

    /**
     * @var Events|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $events;

    /**
     * @var Posts
     */
    protected $fixture;

    public function setUp() {
        $this->events = $this->getMock("Xend\\WordPress\\Events");
        $this->fixture = new Posts($this->events);
    }

    public function testGetPost() {
        $wp_post = new \WP_Post(new \ArrayObject(array('ID' => 1)));
        $this->wordpress()->expects('get_post')->with(1)->willReturn($wp_post);
        $post = $this->fixture->getPost(1);
        $this->assertInstanceOf("Xend\\WordPress\\Posts\\Post", $post);
        $this->assertSame(1, $post->id);
    }

    public function testGetAuthor() {
        /* @var \WP_User|\PHPUnit_Framework_MockObject_MockObject $wp_user */
        $wp_user = $this->getMockBuilder('WP_User')->disableOriginalConstructor()->getMock();
        $wp_user->ID = 3;
        $this->wordpress()->expects('get_user_by')->with('id', 3)->willReturn($wp_user);
        $wp_post = new \WP_Post(new \ArrayObject(array('ID' => 2, 'post_author' => 3)));
        $post = new Posts\Post($wp_post);
        $author = $this->fixture->getAuthor($post);
        $this->assertInstanceOf("Xend\\WordPress\\Users\\User", $author);
        $this->assertSame(3, $author->id);
    }

    /**
     * @depends testGetPost
     */
    public function testGetAuthor2() {
        $wp_post = new \WP_Post(new \ArrayObject(array('ID' => 4, 'post_author' => 5)));
        $this->wordpress()->expects('get_post')->with(4)->willReturn($wp_post);
        $wp_user = $this->getMockBuilder('WP_User')->disableOriginalConstructor()->getMock();
        $wp_user->ID = 5;
        $this->wordpress()->expects('get_user_by')->with('id', 5)->willReturn($wp_user);
        $author = $this->fixture->getAuthor(4);
        $this->assertInstanceOf("Xend\\WordPress\\Users\\User", $author);
        $this->assertSame(5, $author->id);
    }

    public function testGetFilteredTitle() {
        $this->events->expects($this->once())->method('applyFilters')->with('the_title')->willReturnArgument(1);
        $post = new Posts\Post(new \WP_Post(new \ArrayObject(array('post_title' => 'My Test Post'))));
        $this->assertEquals('My Test Post', $this->fixture->getFilteredTitle($post));
    }

    public function testGetAuthorName() {
        $wp_user = $this->getMockBuilder('WP_User')
                        ->disableOriginalConstructor()
                        ->setMethods(null)
                        ->getMock();
        $wp_user->ID = 6;
        $wp_user->data = new \stdClass();
        $wp_user->data->user_nicename = 'Johnny Author';
        $this->wordpress()->expects('get_user_by')->with('id', 6)->willReturn($wp_user);
        $this->events->expects($this->once())->method('applyFilters')->with('the_author')->willReturnArgument(1);
        $post = new Posts\Post(new \WP_Post(new \ArrayObject(array('post_author' => 6))));
        $this->assertEquals('Johnny Author', $this->fixture->getFilteredAuthorName($post));
    }

    public function testGetFilteredExcerpt() {
        $post = new Posts\Post(new \WP_Post(new \ArrayObject(array('post_excerpt' => 'This post is about unit testing.'))));
        $this->events->expects($this->once())->method('applyFilters')->with('get_the_excerpt')->willReturnArgument(1);
        $this->assertEquals('This post is about unit testing.', $this->fixture->getFilteredExcerpt($post));
    }

    public function testGetFilteredContent() {
        $post = new Posts\Post(new \WP_Post(new \ArrayObject(array('post_content' => 'This post is about unit testing.'))));
        $this->events->expects($this->once())->method('applyFilters')->with('get_the_content')->willReturnArgument(1);
        $this->assertEquals('This post is about unit testing.', $this->fixture->getFilteredContent($post));
    }

    public function testGetComments() {
        global $wpdb;
        $wpdb = $this->getMockBuilder("wpdb")->disableOriginalConstructor()->getMock();
        $wpdb->comments = "wp_comments";
        $wpdb->expects($this->once())
             ->method('get_results')
             ->with("SELECT * FROM wp_comments  WHERE ( ( comment_approved = '0' OR comment_approved = '1' ) ) AND comment_post_ID = 7  ORDER BY comment_date_gmt ASC ")
             ->willReturn(array(array('comment_ID' => 8), array('comment_ID' => 9)));
        $wpdb->expects($this->once())->method('prepare')->willReturn('comment_post_ID = 7');
        $this->wordpress()->expects('wp_cache_get', $this->any())->willReturn(false);
        $this->wordpress()
             ->expects('apply_filters_ref_array', $this->any())
             ->willReturnCallback(function($filter, $array) { return $array[0];});

        $comments = $this->fixture->getComments(7);
        $this->assertTrue(is_array($comments));
        $this->assertTrue(count($comments) == 2);
        $this->assertinstanceOf("Xend\\WordPress\\Posts\\Comment", $comments[0]);
        $this->assertSame(8, $comments[0]->id);
        $this->assertinstanceOf("Xend\\WordPress\\Posts\\Comment", $comments[1]);
        $this->assertSame(9, $comments[1]->id);

        unset($GLOBALS['wpdb']);
    }

    public function testGetFilteredPermalink() {
        $post = new Posts\Post(new \WP_Post(new \ArrayObject(array('ID' => 10))));
        $this->wordpress()->expects('get_permalink')->with(10)->willReturn('http://blahblahblah');
        $this->assertEquals('http://blahblahblah', $this->fixture->getFilteredPermalink($post));
    }

    /**
     * @depends testGetFilteredPermalink
     */
    public function testGetFilteredCommentsLink() {
        $post = new Posts\Post(new \WP_Post(new \ArrayObject(array('ID' => 11))));
        $this->wordpress()->expects('get_permalink')->with(11)->willReturn('http://blahblahblah');
        $this->events->expects($this->once())
                     ->method('applyFilters')
                     ->with('get_comments_link', 'http://blahblahblah#comments')
                     ->willReturnArgument(1);
        $this->assertEquals('http://blahblahblah#comments', $this->fixture->getFilteredCommentsLink($post));
    }

    public function testGetFilteredCommentAuthor() {
        $comment = new Posts\Comment(array('comment_author' => 'Johnny Commenter'));
        $this->events->expects($this->once())
                     ->method('applyFilters')
                     ->with('get_comment_author', 'Johnny Commenter')
                     ->willReturnArgument(1);
        $this->assertEquals('Johnny Commenter', $this->fixture->getFilteredCommentAuthor($comment));
    }

    public function testGetFilteredCommentAuthorEmail() {
        $comment = new Posts\Comment(array('comment_author_email' => 'johnny@gmail.com'));
        $this->events->expects($this->once())
                     ->method('applyFilters')
                     ->with('get_comment_author_email', 'johnny@gmail.com')
                     ->willReturnArgument(1);
        $this->assertEquals('johnny@gmail.com', $this->fixture->getFilteredCommentAuthorEmail($comment));
    }

    public function testGetFilteredCommentAuthorUrl() {
        $comment = new Posts\Comment(array('comment_author_url' => 'www.johnny-commenter.com'));
        $this->events->expects($this->once())
                     ->method('applyFilters')
                     ->with('get_comment_author_url', 'www.johnny-commenter.com')
                     ->willReturnArgument(1);
        $this->assertEquals('www.johnny-commenter.com', $this->fixture->getFilteredCommentAuthorUrl($comment));
    }

    public function testGetFilteredCommentAuthorIp() {
        $comment = new Posts\Comment(array('comment_author_IP' => '10.1.2.3'));
        $this->events->expects($this->once())
                     ->method('applyFilters')
                     ->with('get_comment_author_IP', '10.1.2.3')
                     ->willReturnArgument(1);
        $this->assertEquals('10.1.2.3', $this->fixture->getFilteredCommentAuthorIp($comment));
    }

    public function testGetFilteredCommentExcerpt() {
        $comment = new Posts\Comment(array(
                                         'comment_content' => "Hello\r\nWorld!\r\nIt's so very nice to meet you. I'm "
                                                               . "curious, what's it like to be so big and water "
                                                               . "covered?"));
        $this->events->expects($this->once())
                     ->method('applyFilters')
                     ->with('get_comment_excerpt',
                            "Hello World! It's so very nice to meet you. I'm curious, what's it like to be so big and "
                             . "water&nbsp;&hellip;")
                     ->willReturnArgument(1);
        $this->assertEquals(
            "Hello World! It's so very nice to meet you. I'm curious, what's it like to be so big and "
                . "water&nbsp;&hellip;",
            $this->fixture->getFilteredCommentExcerpt($comment));
    }

    public function testGetFilteredCommentContent() {
        $comment = new Posts\Comment(array(
                                         'comment_content' => "Hello\r\nWorld!\r\nIt's so very nice to meet you. I'm "
                                                               . "curious, what's it like to be so big and water "
                                                               . "covered?"));
        $this->events->expects($this->once())
                     ->method('applyFilters')
                     ->with('get_comment_text',
                            "Hello\r\nWorld!\r\nIt's so very nice to meet you. I'm curious, what's it like to be so "
                             . "big and water covered?")
                     ->willReturnArgument(1);
        $this->assertEquals("Hello\r\nWorld!\r\nIt's so very nice to meet you. I'm curious, what's it like to be so "
                             . "big and water covered?",
                            $this->fixture->getFilteredCommentContent($comment));
    }
}