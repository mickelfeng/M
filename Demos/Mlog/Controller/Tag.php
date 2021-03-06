<?php
/**
 * @link https://github.com/MaGuowei
 * @copyright 2013 maguowei.com
 * @author Ma Guowei <imaguowei@gmail.com>
 */

namespace Mlog\Controller;


use M\Extend\Page;
use Mlog\Model\Post;

class Tag extends Common
{
    /**
     * @var array
     */
    public $data = array(
        'title' => 'Tag',
        'nav' => array('标签'=>'Tag/index'),
    );

    public function index($tag)
    {
        $id = \M\App::getRequest()->getParameter(3)?\M\App::getRequest()->getParameter(3):1;
        $tag = urldecode($tag);
        $this->data['title'] = $tag;
        $this->data['nav'] = array('标签'=>'Tag/index',$tag=>"Tag/index/$tag");

        $Post = new Post();

        $Post->join('LEFT JOIN user ON post.author_id=user.u_id');
        $Post->where("`tags` LIKE '%$tag%'");

        $Page = new Page($Post);
        $this->assign('page', array($Page->getPage(),"Tag/index/$tag"));

        $post = $Post->order('post.p_id', 'desc')->limit($id?($id-1)*5:0,5)->select();
        $this->assign('post', $post);
        $this->display('Tag/index');
    }

    public function tags()
    {
        $this->layout = 'tag';
        $this->data['title'] = '全部标签';

        $Post = new Post();
        $this->assign('Post',$Post);
        $this->display('Tag/tags');
    }
}