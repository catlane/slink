<?php
/**
 * 执行生成流程
 * @author yangyanlei
 * @email yangyanlei@dangdang.com
 * Ctime 2020/9/15
 */
namespace Slink\Process;

use Slink\Component\Single;
use Slink\Cache\Redis;
use Slink\Component\Conver;
use Slink\Exceptions\SLinkException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Olink
{
    use Single;

    private $shortLink;

    private $originLink;

    //存储下标
    private $save_alias;


    /**
     * @param string|null $short_link
     *
     * @throws SLinkException
     */
    public function __construct(?string $short_link)
    {
        if (empty($short_link)) {
            throw new SLinkException('未传递链接参数');
        }
        $this->shortLink = $short_link;
    }

    /**
     * 开始流程
     */
    public function start()
    {
        //检查是否存在
        $this->checkLink();
        $this->getOlink();

        return $this->originLink ?? '';
    }

    //检查短链是否存在
    /**
     * @throws SLinkException
     */
    private function checkLink()
    {
        //获取短链的最后一位数字
        $this->save_alias = substr($this->shortLink, -1);
        $is_exist = Redis::getInstance()->checkLinkHash($this->save_alias, $this->shortLink);
        if (!$is_exist) {
            throw new SLinkException('获取失败');
        }
    }

    //生成短链


    /**
     * @throws SLinkException
     */
    private function getOlink() : void
    {
        $originLinkEncode = Redis::getInstance()->getLinkHash($this->save_alias, $this->shortLink);
        $this->originLink = urldecode($originLinkEncode);
        if (empty($originLinkEncode) || !$this->originLink = urldecode($originLinkEncode)) {
            throw new SLinkException('获取失败');
        }
    }
}
