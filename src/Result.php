<?php namespace MattyRad\Support;

interface Result
{
    public function isSuccess();
    public function isFailure();
    public function getReason();
}