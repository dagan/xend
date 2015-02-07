<?php

namespace Xend\WordPress;

class ElementsTest extends TestCase {

    /**
     * @var Events|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $events;

    /**
     * @var Elements
     */
    protected $fixture;

    /**
     * @var \WP_Widget_Factory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $widgetFactory;

    public function setUp() {
        global $wp_widget_factory;
        $this->widgetFactory = $wp_widget_factory = $this->getMockBuilder("WP_Widget_Factory")
                                                         ->disableOriginalConstructor()
                                                         ->getMock();
        $this->events = $this->getMockBuilder("Xend\\WordPress\\Events")->getMock();
        $this->fixture = new Elements($this->events);
    }

    public function tearDown() {
        unset($GLOBALS['wp_widget_factory']);
    }

    public function testConstructorRegisterInitWidgets() {
        $this->events->expects($this->once())
                     ->method('addAction')
                     ->with('widgets_init', new \PHPUnit_Framework_Constraint_ArraySubset(array(
                                                                                              $this->fixture,
                                                                                              'initWidgets'
                                                                                          )));
        new Elements($this->events);
    }

    public function testRegisterMenuLocations() {
        $expectedLocations = array(
            'Top'    => 'My top menu location',
            'Bottom' => 'My bottom menu location'
        );
        $this->wordpress()->expects('register_nav_menus', $this->once())->with($expectedLocations);
        $this->fixture->registerMenuLocations($expectedLocations);
    }

    /**
     * @depends testRegisterMenuLocations
     */
    public function testRegisterMenuLocation() {
        $this->wordpress()->expects('register_nav_menus', $this->once())->with(array('Top' => 'My top menu location'));
        $this->fixture->registerMenuLocation('Top', 'My top menu location');
    }

    public function testRegisterSidebar() {
        $expectedOptions = array(
            'name'          => 'My Sidebar',
            'description'   => 'My unit-testing sidebar',
            'class'         => 'unit-test',
            'before_widget' => '<li>',
            'after_widget'  => '</li>',
            'before_title'  => '<h2>',
            'after_title'   => '</h2>'
        );

        $sidebar = new Elements\Sidebar($expectedOptions['name'], $expectedOptions['description']);
        $sidebar->class = "unit-test";
        $sidebar->beforeWidget = "<li>";
        $sidebar->afterWidget = "</li>";
        $sidebar->beforeTitle = "<h2>";
        $sidebar->afterTitle = "</h2>";

        $this->wordpress()->expects('register_sidebar', $this->once())->with($expectedOptions)->willReturn('sidebar-1');
        $this->fixture->registerSidebar($sidebar);
        $this->assertNull($sidebar->id);
        $this->fixture->initWidgets();
        $this->assertEquals('sidebar-1', $sidebar->id);
    }

    public function testRegisterSidebar2() {
        $sidebar = new Elements\Sidebar();
        $sidebar->id = 'my-sidebar';

        $this->wordpress()
             ->expects('register_sidebar', $this->once())
             ->with(array('id' => 'my-sidebar'))
             ->willReturn('my-sidebar');
        $this->fixture->registerSidebar($sidebar);
        $this->fixture->initWidgets();
        $this->assertEquals('my-sidebar', $sidebar->id);
    }

    /**
     * @expectedException Xend\WordPress\Exception
     */
    public function testRegisterSidebar3() {
        $this->fixture->initWidgets();
        $this->fixture->registerSidebar(new Elements\Sidebar());
    }

    public function testRegisterWidget() {
        $widget = $this->getMockBuilder("Xend\\WordPress\\Elements\\Widget")->disableOriginalConstructor()->getMock();
        $widget->id_base = "test-widget";
        $this->widgetFactory->expects($this->never())->method('register');
        $this->fixture->registerWidget($widget);
        $this->fixture->initWidgets();
        $this->assertEquals($widget, $this->widgetFactory->widgets[$widget->id_base]);
    }

    public function testRegisterWidget2() {
        $this->widgetFactory->expects($this->once())->method('register')->with('TestWidget');
        $this->fixture->registerWidget('TestWidget');
        $this->fixture->initWidgets();
    }

    /**
     * @expectedException Xend\WordPress\Exception
     */
    public function testRegisterWidget3() {
        $this->fixture->initWidgets();
        $this->fixture->registerWidget('TestWidget');
    }
}