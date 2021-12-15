<?php

interface IDatabase
{
    public static function getInstance(): IDatabase;

    public function query();

    public function selectCell();

    public function selectRow();

    public function selectCol();
}