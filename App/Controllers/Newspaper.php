<?php

namespace App\Controllers;

use App\Models\ArticleComment;
use App\Models\ArticleCommentVote;
use App\Models\ArticleVote;
use App\Models\NewspaperArticle;
use App\Models\Newspaper as NewspaperModel;
use App\System\App;
use App\System\AppException;
use App\System\Controller;
use App\System\Input;

class Newspaper extends Controller
{
    public function showCreateForm ()
    {
        // only 1 newspaper per user is allowed
        $myNewspaper = App::user()->getNewspaper();

        if (!empty($myNewspaper)) {
            App::redirect("/newspaper/" . $myNewspaper->id);
        }

        return $this->render('news/createNewspaper.html.twig', [
            "creationCost" => NewspaperModel::CREATION_COST
        ]);
    }

    public function showNewspaper ($id)
    {
        $newsPaper = NewspaperModel::find($id);

        if (empty($newsPaper)) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $articles = NewspaperArticle::where([
            "uid" => $newsPaper->uid
        ])->get();

        if (empty($articles)) {
            $articles = [];
        } else {
            $articles = $articles->toArray();
        }

        return $this->render('news/newspaperProfile.html.twig', [
            "newspaper" => $newsPaper->toArray(),
            "articles" => $articles
        ]);
    }

    public function showCreateArticle ()
    {
        // ensure that he has a newspaper
        if (empty(App::user()->getNewspaper())) {
            throw new AppException(AppException::INVALID_DATA);
        }

        return $this->render('news/create.html.twig');
    }

    public function comment ()
    {
        $channelId = Input::getInteger("channelId");
        $channelType = Input::getInteger("channelType");
        $message = Input::getString("message", true);

        if ($channelId < 1 || !in_array($channelType, ArticleComment::$validTypes) || strlen($message) < 10) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $success = ArticleComment::create([
            "channel_id" => $channelId,
            "channel_type" => $channelType,
            "message" => $message,
            "uid" => App::user()->getUid()
        ]);

        if ($success) {
            return $success->id;
        }

        throw new AppException(AppException::ACTION_FAILED);
    }

    public function voteComment ()
    {
        $id = Input::getInteger("id");
        $uid = App::user()->getUid();

        if ($id < 1) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $message = ArticleCommentVote::find($id);

        if (!$message) {
            throw new AppException(AppException::INVALID_DATA);
        }

        // check if he has already voted
        $vote = ArticleCommentVote::where([
            "message" => $id,
            "voter" => $uid
        ])->first();

        if ($vote) {
            throw new AppException(AppException::INVALID_DATA);
        }

        ArticleCommentVote::create([
            "message" => $id,
            "voter" => $uid,
        ]);

        return true;
    }

    public function deleteArticle ()
    {
        $id = Input::getInteger("id");

        if ($id < 1) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $article = NewspaperArticle::find($id);

        // check if he is allowed to remove this
        if (!$article || (!App::user()->isAdminOrMod() && $article->uid != App::user()->getUid())) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $article->delete();

        return true;
    }

    public function getArticlesByCategory ()
    {
        $category = Input::getInteger("category");

        if (!in_array($category, NewspaperArticle::$validCategories)) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $articles = NewspaperArticle::with("newspaper")->where([
            "category" => $category,
            "country" => App::user()->getLocation()["country"]["id"]
        ])->get();

        if ($articles) {
            $articles = $articles->toArray();
        } else {
            $articles = [];
        }

        return $articles;
    }

    public function create ()
    {
        $name = Input::getString("name", true);
        $description = Input::getString("description", true);
        $uid = App::user()->getUid();

        if (strlen($name) < 4 || strlen($description) < 10) {
            throw new AppException(AppException::INVALID_DATA);
        }

        // only 1 newspaper per user is allowed
        $newsPaper = NewspaperModel::where([
            "uid" => $uid
        ])->first();

        if ($newsPaper) {
            throw new AppException(AppException::INVALID_DATA);
        }

        App::user()->buy(NewspaperModel::CREATION_COST, "gold", "NEWSPAPER");


        $success = NewspaperModel::create([
            "name" => $name,
            "description" => $description,
            "country" => App::user()->getLocation()["country"]["id"],
            "uid" => $uid
        ]);

        if ($success) {
            return $success->id;
        }

        throw new AppException(AppException::ACTION_FAILED);
    }

    public function showArticle ($id)
    {
        if ($id < 1) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $article = NewspaperArticle::find($id);

        if (!$article) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $article->views++;
        $article->save();

        return $this->render('news/article.html.twig', [
            "article" => $article->toArray()
        ]);
    }

    public function publishArticle ()
    {
        $title = Input::getString("title", true);
        $text = Input::getString("text", true);
        $category = Input::getInteger("category");

        if (strlen($title) < 4 || strlen($text) < 10 || !in_array($category, NewspaperArticle::$validCategories)) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $myNewspaper = App::user()->getNewspaper();

        if (!$myNewspaper) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $success = NewspaperArticle::create([
            "title" => $title,
            "text" => $text,
            "category" => $category,
            "uid" => App::user()->getUid(),
            "country" => $myNewspaper->country
        ]);

        if ($success) {
            return $success->id;
        }

        throw new AppException(AppException::ACTION_FAILED);
    }

    public function voteArticle ()
    {
        $id = Input::getInteger("id");

        if ($id < 1) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $query = [
            "article" => $id,
            "uid" => App::user()->getUid()
        ];

        $hasAlreadyVoted = ArticleVote::where($query);

        if ($hasAlreadyVoted) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $article = NewspaperArticle::find($id);

        if (!$article) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $article->votes++;
        $article->save();

        ArticleVote::create($query);

        return true;
    }

    public function editArticle ()
    {
        $id = Input::getInteger("id");
        $title = Input::getString("title", true);
        $text = Input::getString("text", true);

        if (strlen($title) < 4 || strlen($text) < 10 || $id < 1) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $article = NewspaperArticle::find($id);

        if (!$article || $article->uid != App::user()->getUid()) {
            throw new AppException(AppException::INVALID_DATA);
        }

        $article->title = $title;
        $article->text = $text;
        $article->save();

        return true;
    }
}