<?php


namespace Xend\WordPress;


class QueryTest extends TestCase {

    /**
     * @var Posts|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $posts;

    /**
     * @var Events|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $events;

    /**
     * @var \WP_Query|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $wp_query;

    /**
     * @var Query|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture;

    public function setUp() {
        $this->events = $this->getMock("Xend\\WordPress\\Events");
        $this->posts = $this->getMockBuilder("Xend\\WordPress\\Posts")
                            ->setConstructorArgs(array($this->events))
                            ->getMock();
        $this->wp_query = $this->getMock("WP_Query");

        $this->fixture = new Query($this->wp_query, $this->posts, $this->events);
    }

    public function testCurrentPost() {
        global $authordata;
        $authordata = $this->getMockBuilder("WP_User")->disableOriginalConstructor()->getMock();
        $this->wp_query->posts = array(1, new \WP_Post(new \ArrayObject(array('ID' => 1))));
        $this->wp_query->current_post = 1;

        $postContext = $this->fixture->currentPost();
        $this->assertInstanceOf("\\Xend\\WordPress\\Posts\\PostContext", $postContext);
        $this->assertEquals(1, $postContext->id);

        unset($GLOBALS['authordata']);
    }

    public function testPosts() {
        $this->assertInstanceOf("\\Xend\\WordPress\\Query\\PostIterator", $this->fixture->posts());
    }

    public function testCountPosts() {
        $this->wp_query->post_count = 3;
        $this->assertEquals(3, $this->fixture->countPosts());
    }

    public function testCountTotalPosts() {
        $this->wp_query->found_posts = 6;
        $this->assertEquals(6, $this->fixture->countTotalPosts());
    }

    public function testComments() {
        global $wpdb;
        $wpdb = $this->getMockBuilder("wpdb")->disableOriginalConstructor()->getMock();
        $wpdb->comments = "wp_comments";
        $wpdb->expects($this->once())
             ->method('get_results')
             ->with("SELECT * FROM wp_comments  WHERE ( comment_approved = '1' ) AND comment_post_ID = 1  ORDER BY comment_date_gmt ASC ")
             ->willReturn(array());
        $wpdb->expects($this->once())->method('prepare')->willReturn('comment_post_ID = 1');
        $this->wordpress()->expects('wp_cache_get', $this->any())->willReturn(false);
        $this->wordpress()
             ->expects('apply_filters_ref_array', $this->any())
             ->willReturnCallback(function($filter, $array) { return $array[0];});

        $this->wp_query->in_the_loop = true;
        $this->wp_query->post = new \WP_Post(new \ArrayObject(array('ID' => 1)));

        $this->assertInstanceof("\\Xend\\WordPress\\Query\\CommentIterator", $this->fixture->comments());

        unset($GLOBALS['wpdb']);
    }

    public function testCommentsOutsideTheLoopThrowsException() {
        try {
            $this->fixture->comments();
            $this->assertFail("Calling Query::comments() outside the loop did not throw and exception");
        } catch (\Xend\Exception $ex) {
            // Expected
        }
    }

    public function testHasComments() {
        $this->assertFalse($this->fixture->hasComments());
        $this->wp_query->post = new \WP_Post(new \ArrayObject(array('comment_count' => 1)));
        $this->assertTrue($this->fixture->hasComments());
    }

    public function testCountComments() {
        $this->wp_query->post = new \WP_Post(new \ArrayObject(array('comment_count' => 5)));
        $this->assertEquals(5, $this->fixture->countComments());
    }

    public function testCountComments2() {
        $this->assertEquals(0, $this->fixture->countComments());
    }

    public function testGetQueryType() {
        $this->wp_query->is_404 = true;
        $this->assertEquals('error', $this->fixture->getQueryType());
        $this->assertEquals(array('error', '404', 'type' => 'error', 'subtype' => '404'), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType2() {
        $this->wp_query->is_admin = true;
        $this->assertEquals('admin', $this->fixture->getQueryType());
        $this->assertEquals(array('admin', null, 'type' => 'admin', 'subtype' => null), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType3() {
        $this->wp_query->is_comment_feed = true;
        $this->assertEquals('comment', $this->fixture->getQueryType());
        $this->assertEquals(array('comment', 'feed', 'type' => 'comment', 'subtype' => 'feed'), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType4() {
        $this->wp_query->is_comments_popup = true;
        $this->assertEquals('comment', $this->fixture->getQueryType());
        $this->assertEquals(array('comment', 'popup', 'type' => 'comment', 'subtype' => 'popup'), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType5() {
        $this->wp_query->is_home = true;
        $this->assertEquals('index', $this->fixture->getQueryType());
        $this->assertEquals(array('index', 'home', 'type' => 'index', 'subtype' => 'home'), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType6() {
        $this->wp_query->expects($this->exactly(2))->method('is_front_page')->willReturn(true);
        $this->assertEquals('index', $this->fixture->getQueryType());
        $this->assertEquals(array('index', 'front', 'type' => 'index', 'subtype' => 'front'), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType7() {
        $this->wp_query->expects($this->exactly(5))->method('is_singular')->willReturn(true);
        $this->wp_query->expects($this->exactly(5))
                       ->method('get_queried_object')
                       ->willReturnOnConsecutiveCalls(
                                           new \ArrayObject(array('post_type' => 'post'), \ArrayObject::ARRAY_AS_PROPS),
                                           new \ArrayObject(array('post_type' => 'post'), \ArrayObject::ARRAY_AS_PROPS),
                                           new \ArrayObject(array('post_type' => 'page'), \ArrayObject::ARRAY_AS_PROPS),
                                           new \ArrayObject(array('post_type' => 'attachment'), \ArrayObject::ARRAY_AS_PROPS),
                                           new \ArrayObject(array('post_type' => 'custom'), \ArrayObject::ARRAY_AS_PROPS)
                                       );

        $this->assertEquals('single', $this->fixture->getQueryType());
        $this->assertEquals(array('single', 'post', 'type' => 'single', 'subtype' => 'post'), $this->fixture->getQueryType(true));
        $this->assertEquals(array('single', 'page', 'type' => 'single', 'subtype' => 'page'), $this->fixture->getQueryType(true));
        $this->assertEquals(array('single', 'attachment', 'type' => 'single', 'subtype' => 'attachment'), $this->fixture->getQueryType(true));
        $this->assertEquals(array('single', 'custom', 'type' => 'single', 'subtype' => 'custom'), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType8() {
        $this->wp_query->expects($this->exactly(2))->method('is_post_type_archive')->willReturn(true);
        $this->wp_query->expects($this->exactly(2))->method('get_queried_object')->willReturn(
            new \ArrayObject(array('slug' => 'custom'), \ArrayObject::ARRAY_AS_PROPS));

        $this->assertEquals('archive', $this->fixture->getQueryType());
        $this->assertEquals(array('archive', 'custom', 'type' => 'archive', 'subtype' => 'custom'), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType9() {
        $this->wp_query->expects($this->exactly(2))->method('is_category')->willReturn(true);
        $this->wp_query->expects($this->exactly(2))->method('get_queried_object')->willReturn(
            new \ArrayObject(array('slug' => 'unit-test'), \ArrayObject::ARRAY_AS_PROPS));

        $this->assertEquals('category', $this->fixture->getQueryType());
        $this->assertEquals(array('category', 'unit-test', 'type' => 'category', 'subtype' => 'unit-test'), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType10() {
        $this->wp_query->expects($this->exactly(2))->method('is_tag')->willReturn(true);
        $this->wp_query->expects($this->exactly(2))->method('get_queried_object')->willReturn(
            new \ArrayObject(array('slug' => 'unit-test'), \ArrayObject::ARRAY_AS_PROPS));

        $this->assertEquals('tag', $this->fixture->getQueryType());
        $this->assertEquals(array('tag', 'unit-test', 'type' => 'tag', 'subtype' => 'unit-test'), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType11() {
        $this->wp_query->expects($this->exactly(2))->method('is_tax')->willReturn(true);
        $this->wp_query->expects($this->exactly(2))->method('get_queried_object')->willReturn(
            new \ArrayObject(array('slug' => 'unit-test'), \ArrayObject::ARRAY_AS_PROPS));

        $this->assertEquals('taxonomy', $this->fixture->getQueryType());
        $this->assertEquals(array('taxonomy', 'unit-test', 'type' => 'taxonomy', 'subtype' => 'unit-test'), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType12() {
        $this->wp_query->is_search = true;
        $this->wp_query->expects($this->exactly(3))
                       ->method('get')
                       ->with('post_type')
                       ->willReturnOnConsecutiveCalls(null, null, 'custom');

        $this->assertEquals('search', $this->fixture->getQueryType());
        $this->assertEquals(array('search', 'index', 'type' => 'search', 'subtype' => 'index'), $this->fixture->getQueryType(true));
        $this->assertEquals(array('search', 'custom', 'type' => 'search', 'subtype' => 'custom'), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType13() {
        $this->wp_query->expects($this->exactly(2))->method('is_author')->willReturn(true);
        $this->wp_query->expects($this->exactly(2))
                       ->method('get_queried_object')
                       ->willReturn(new \ArrayObject(array('id' => 5), \ArrayObject::ARRAY_AS_PROPS));

        $this->assertEquals('author', $this->fixture->getQueryType());
        $this->assertEquals(array('author', 5, 'type' => 'author', 'subtype' => 5), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType14() {
        $this->wp_query->is_date = true;
        $this->assertEquals('date', $this->fixture->getQueryType());
        $this->assertEquals(array('date', 'index', 'type' => 'date', 'subtype' => 'index'), $this->fixture->getQueryType(true));

        $this->wp_query->is_time = true;
        $this->assertEquals(array('date', 'time', 'type' => 'date', 'subtype' => 'time'), $this->fixture->getQueryType(true));
        $this->wp_query->is_time = false;

        $this->wp_query->is_day = true;
        $this->assertEquals(array('date', 'day', 'type' => 'date', 'subtype' => 'day'), $this->fixture->getQueryType(true));
        $this->wp_query->is_day = false;

        $this->wp_query->is_month = true;
        $this->assertEquals(array('date', 'month', 'type' => 'date', 'subtype' => 'month'), $this->fixture->getQueryType(true));
        $this->wp_query->is_month = false;

        $this->wp_query->is_year = true;
        $this->assertEquals(array('date', 'year', 'type' => 'date', 'subtype' => 'year'), $this->fixture->getQueryType(true));
        $this->wp_query->is_year = false;
    }

    public function testGetQueryType15() {
        $this->wp_query->expects($this->exactly(3))->method('is_feed')->willReturn(true);
        $this->wp_query->expects($this->exactly(6))
                       ->method('get')
                       ->willReturnOnConsecutiveCalls('feed', null, 'feed', null, 'atom', 'custom');
        $this->wordpress()->expects('get_default_feed', $this->exactly(2))->willReturn('rss');

        $this->assertEquals('rss', $this->fixture->getQueryType());
        $this->assertEquals(array('rss', 'index', 'type' => 'rss', 'subtype' => 'index'), $this->fixture->getQueryType(true));
        $this->assertEquals(array('atom', 'custom', 'type' => 'atom', 'subtype' => 'custom'), $this->fixture->getQueryType(true));
    }

    public function testGetQueryType16() {
        $this->wp_query->expects($this->exactly(2))
                       ->method('get_queried_object')
                       ->willReturn(new \ArrayObject(array('slug' => 'custom'), \ArrayObject::ARRAY_AS_PROPS));

        $this->assertEquals('archive', $this->fixture->getQueryType());
        $this->assertEquals(array('archive', 'custom', 'type' => 'archive', 'subtype' => 'custom'), $this->fixture->getQueryType(true));
    }

    public function testGetQueriedObject() {
        $object = new \stdClass();
        $this->wp_query->expects($this->once())->method('get_queried_object')->willReturn($object);
        $this->assertSame($object, $this->fixture->getQueriedObject());
    }

    public function testInTheLoop() {
        $this->wp_query->in_the_loop = true;
        $this->assertTrue($this->fixture->inTheLoop());
        $this->wp_query->in_the_loop = false;
        $this->assertFalse($this->fixture->inTheLoop());
    }

    public function testIs404() {
        $this->wp_query->is_404 = true;
        $this->assertTrue($this->fixture->is404());
        $this->wp_query->is_404 = false;
        $this->assertFalse($this->fixture->is404());
    }

    public function testIsAdmin() {
        $this->wp_query->is_admin = true;
        $this->assertTrue($this->fixture->isAdmin());
        $this->wp_query->is_admin = false;
        $this->assertFalse($this->fixture->isAdmin());
    }

    public function testIsArchive() {
        $this->wp_query->is_archive = true;
        $this->assertTrue($this->fixture->isArchive());
        $this->wp_query->is_archive = false;
        $this->assertFalse($this->fixture->isArchive());
    }

    public function testIsAuthor() {
        $this->wp_query->expects($this->once())->method('is_author')->with('unit-test')->willReturn(true);
        $this->fixture->isAuthor('unit-test');
    }

    public function testIsCategory() {
        $this->wp_query->expects($this->once())->method('is_category')->with('unit-test')->willReturn(true);
        $this->fixture->isCategory('unit-test');
    }

    public function testIsCommentFeed() {
        $this->wp_query->is_comment_feed = true;
        $this->assertTrue($this->fixture->isCommentFeed());
        $this->wp_query->is_comment_feed = false;
        $this->assertFalse($this->fixture->isCommentFeed());
    }

    public function testIsDate() {
        $this->wp_query->is_date = true;
        $this->assertTrue($this->fixture->isDate());
        $this->wp_query->is_date = false;
        $this->assertFalse($this->fixture->isDate());
    }

    public function testIsDay() {
        $this->wp_query->is_day = true;
        $this->assertTrue($this->fixture->isDay());
        $this->wp_query->is_day = false;
        $this->assertFalse($this->fixture->isDay());
    }

    public function testIsFeed() {
        $this->wp_query->expects($this->once())->method('is_feed')->with('unit-test')->willReturn(true);
        $this->assertTrue($this->fixture->isFeed('unit-test'));
    }

    public function testIsFrontPage() {
        $this->wp_query->expects($this->once())->method('is_front_page')->willReturn(true);
        $this->fixture->isFrontPage();
    }

    public function testIsHome() {
        $this->wp_query->is_home = true;
        $this->assertTrue($this->fixture->isHome());
        $this->wp_query->is_home = false;
        $this->assertFalse($this->fixture->isHome());
    }

    public function testIsMonth() {
        $this->wp_query->is_month = true;
        $this->assertTrue($this->fixture->isMonth());
        $this->wp_query->is_month = false;
        $this->assertFalse($this->fixture->isMonth());
    }

    public function testIsPage() {
        $this->wp_query->expects($this->once())->method('is_page')->with('unit-test')->willReturn(true);
        $this->assertTrue($this->fixture->isPage('unit-test'));
    }

    public function testIsPaged() {
        $this->wp_query->is_paged = true;
        $this->assertTrue($this->fixture->isPaged());
        $this->wp_query->is_paged = false;
        $this->assertFalse($this->fixture->isPaged());
    }

    public function testIsPostsPage() {
        $this->wp_query->is_posts_page = true;
        $this->assertTrue($this->fixture->isPostsPage());
        $this->wp_query->is_posts_page = false;
        $this->assertFalse($this->fixture->isPostsPage());
    }

    public function testIsPostTypeArchive() {
        $this->wp_query->expects($this->once())->method('is_post_type_archive')->willReturn(true);
        $this->fixture->isPostTypeArchive();
    }

    public function testIsSingle() {
        $this->wp_query->expects($this->once())->method('is_single')->with('unit-test')->willReturn(true);
        $this->assertTrue($this->fixture->isSingle('unit-test'));
    }

    public function testIsSingular() {
        $this->wp_query->expects($this->once())->method('is_singular')->with('unit-test')->willReturn(true);
        $this->assertTrue($this->fixture->isSingular('unit-test'));
    }

    public function testIsTag() {
        $this->wp_query->expects($this->once())->method('is_tag')->with('unit-test')->willReturn(true);
        $this->assertTrue($this->fixture->isTag('unit-test'));
    }

    public function testIsTax() {
        $this->wp_query->expects($this->once())->method('is_tax')->with('unit', 'test')->willReturn(true);
        $this->assertTrue($this->fixture->isTax('unit', 'test'));
    }

    public function testIsTaxonomy() {
        $this->wp_query->expects($this->once())->method('is_tax')->with('unit', 'test')->willReturn(true);
        $this->assertTrue($this->fixture->isTaxonomy('unit', 'test'));
    }

    public function testIsTime() {
        $this->wp_query->is_time = true;
        $this->assertTrue($this->fixture->isTime());
        $this->wp_query->is_time = false;
        $this->assertFalse($this->fixture->isTime());
    }

    public function testIsTrackback() {
        $this->wp_query->is_trackback = true;
        $this->assertTrue($this->fixture->isTrackback());
        $this->wp_query->is_trackback = false;
        $this->assertFalse($this->fixture->isTrackback());
    }

    public function testIsYear() {
        $this->wp_query->is_year = true;
        $this->assertTrue($this->fixture->isYear());
        $this->wp_query->is_year = false;
        $this->assertFalse($this->fixture->isYear());
    }
}