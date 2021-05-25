<?php
/**
 * Created by PhpStorm.
 * Name:  Ubel Angel Fonseca Cedeño
 * Email: ubelangelfonseca@gmail.com
 * Date:  27/4/21
 * Time:  15:57
 */

namespace App\Exception;


class AnswerRewriteException extends \Exception {


    /**
     * AnswerRewriteException constructor.
     */
    public function __construct()
    {
        parent::__construct("Not allowed more answers",300);
    }
}