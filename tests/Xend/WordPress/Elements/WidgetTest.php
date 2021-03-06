<?php

namespace Xend\WordPress\Elements;

class WidgetTest extends \Xend\WordPress\TestCase {

    public function testWidget() {

        $controller = $this->getMock("Xend\\WordPress\\Elements\\Widget\\ControllerInterface");
        $fixture = new \Xend\WordPress\Elements\Widget('Unit Test', $controller);

        $instance = array('title' => 'My Unit Test Widget');
        $args     = array('before_widget' => '<li>', 'after_widget' => '</li>', 'before_title' => '<h2>',
            'after_title' => '</h2>');

        $controller->expects($this->once())
                   ->method('renderWidget')
                   ->with(
                       new \PHPUnit_Framework_Constraint_IsIdentical($fixture),
                       new \PHPUnit_Framework_Constraint_IsIdentical($instance),
                       new \PHPUnit_Framework_Constraint_IsIdentical($args));

        $fixture->widget($args, $instance);
    }

    public function testForm() {

        $controller = $this->getMock("Xend\\WordPress\\Elements\\Widget\\ControllerInterface");
        $fixture = new \Xend\WordPress\Elements\Widget('Unit Test', $controller);

        $instance = array('title' => 'My Unit Test Widget');

        $controller->expects($this->once())
            ->method('renderForm')
            ->with(
                new \PHPUnit_Framework_Constraint_IsIdentical($fixture),
                new \PHPUnit_Framework_Constraint_IsIdentical($instance));

        $fixture->form($instance);
    }

    public function testUpdate() {
        $controller = $this->getMock("Xend\\WordPress\\Elements\\Widget\\ControllerInterface");
        $fixture = new \Xend\Wordpress\Elements\Widget('Unit Test', $controller);

        $newInstance = array('title' => 'My Unit Test Widget');
        $oldInstance = array('title' => 'My New Unit Test Title');

        $controller->expects($this->once())
                   ->method('filterUpdate')
                   ->with(
                       new \PHPUnit_Framework_Constraint_IsIdentical($fixture),
                       new \PHPUnit_Framework_Constraint_IsIdentical($newInstance),
                       new \PHPUnit_Framework_Constraint_IsIdentical($oldInstance))
                   ->willReturnArgument(2);

        $fixture->update($newInstance, $oldInstance);
    }
}