<?php

namespace Xend\WordPress\Query;

/**
 * Query
 *
 * @author Dagan
 */
interface QueryInterface {

    /**
     * Check Whether the Query Has Posts
     * @return bool
     */
    public function hasPosts();

    /**
     * Retrieve the Number of Posts in the Query
     * @return int
     */
    public function countPosts();

    /**
     * Retrieve the Total Number of Posts in the Query
     * @return int
     */
    public function countTotalPosts();

    /**
     * Retireve a Posts Iterator
     * @return \Iterator
     */
    public function posts();

    /**
     * Retrieve the Current Post
     * @return \Xend\WordPress\Posts\Post|false
     */
    public function currentPost();

    /**
     * Check Whether the Post Has Comments
     * @return bool
     */
    public function hasComments();

    /**
     * Retrieve the Number of Comments on the Current Post
     * @return int
     */
    public function countComments();

    /**
     * Retrieve a Comments Iterator
     * @return \Iterator
     */
    public function comments();

    /**
     * Returns the Currently Queried Object
     *
     * @return \stdClass|null
     */
    public function getQueriedObject();

    /**
     * Retrieve the Query Type
     * @param bool $include_subtype
     * @return string|array
     */
    public function getQueryType($include_subtype = false);

    public function inTheLoop();

    public function is404();

    public function isAdmin();

    public function isArchive();

    public function isAttachment();

    /**
     * @param string|integer|array $author
     * @return bool
     */
    public function isAuthor($author = '');

    /**
     * @param string|integer|array $category
     * @return bool
     */
    public function isCategory($category = '');

    public function isCommentFeed();

    public function isCommentsPopup();

    public function isDate();

    public function isDay();

    /**
     * @param string|array
     * @return bool
     */
    public function isFeed($feed = '');

    public function isFrontPage();

    public function isHome();

    public function isMonth();

    /**
     * @param string|integer|array
     * @return bool
     */
    public function isPage($page = '');

    public function isPaged();

    public function isPostsPage();

    public function isPostTypeArchive();

    public function isPreview();

    public function isRobots();

    public function isSearch();

    /**
     * @param string|integer|array
     * @return bool
     */
    public function isSingle($post = '');

    /**
     * @param string|array
     * @return bool
     */
    public function isSingular($postType = '');

    /**
     * @param string|integer|array
     * @return bool
     */
    public function isTag($tag = '');

    /**
     * @param string|array $taxonomy
     * @param string|integer|array $term
     * @return mixed
     */
    public function isTax($taxonomy = '', $term = '');

    /**
     * @param string|array $taxonomy
     * @param string|integer|array $term
     * @return mixed
     */
    public function isTaxonomy($taxonomy = '', $term = '');

    public function isTime();

    public function isTrackback();

    public function isYear();
}
