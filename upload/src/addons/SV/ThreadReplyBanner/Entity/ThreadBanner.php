<?php

namespace SV\ThreadReplyBanner\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure as EntityStructure;
use XF\Phrase;
use SV\ThreadReplyBanner\XF\Entity\Thread as ExtendedThreadEntity;

/**
 * COLUMNS
 * @property int thread_id
 *
 * RELATIONS
 * @property ExtendedThreadEntity Thread
 */
class ThreadBanner extends AbstractBanner
{
    const SUPPORTS_MOD_LOG = true;

    public function canView(Phrase &$error = null): bool
    {
        return $this->Thread->canViewSvContentReplyBanner($error);
    }

    public function canEdit(Phrase &$error = null): bool
    {
        return $this->Thread->canManageSvContentReplyBanner($error);
    }

    /**
     * @since 2.4.0
     *
     * @param Phrase|null $error
     *
     * @return bool
     */
    public function canViewEditHistory(Phrase &$error = null): bool
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            return false;
        }

        if (!$this->app()->options()->editHistory['enabled'])
        {
            return false;
        }

        return $this->Thread->canManageSvContentReplyBanner($error);
    }

    public function getAssociatedContent(): Entity
    {
        return $this->Thread;
    }

    public static function getStructure(EntityStructure $structure) : EntityStructure
    {
        static::setupDefaultStructure(
            $structure,
            'xf_sv_thread_banner',
            'SV\ThreadReplyBanner:ThreadBanner',
            'sv_thread_banner',
            'thread_id'
        );

        $structure->relations['Thread'] = [
            'entity'     => 'XF:Thread',
            'type'       => self::TO_ONE,
            'conditions' => 'thread_id',
            'primary'    => true,
        ];

        return $structure;
    }
}