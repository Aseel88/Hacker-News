<?php
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/views/header.php';

$errors = [];

$statement = $pdo->query('SELECT * FROM posts ORDER BY createdAt DESC');

if (!$statement) {
    die(var_dump($pdo->errorInfo()));
}
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);


$statement = $pdo->query('SELECT * FROM users');
if (!$statement) {
    die(var_dump($pdo->errorInfo()));
}
$users = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <article>

        <h1><?php echo $config['title']; ?></h1>

        <?php if (isset($_SESSION['user'])) : ?>
            <p>Welcome
                <?php echo $_SESSION['user']['firstName']; ?>
                !
            </p>
        <?php endif; ?>

        <ul>
            <li>
                <a href="newestPosts.php"> Newest</a>
            </li>
            <li>
                <a href="mostPopular.php"> Most popular</a>
            </li>
            <li class="createPosts">
                <?php if (isset($_SESSION['user'])) : ?>
                    <a href="createPost.php"> Create your own post!</a>
                <?php endif; ?>
            </li>
        </ul>


        <div class="posts">
            <ol class="olPosts">
                <?php foreach ($posts as $post) : ?>
                    <li class="li">
                        <div class="title">
                            <a href="<?php echo $post['link']; ?>"> <?php echo $post['title']; ?></a>
                        </div>
                        <div class="description">
                            <p><?php echo $post['description']; ?></p>
                        </div>
                        <div class="time">
                            <time class="text-secondary"><?= formatDate($post['createdAt']); ?></time>
                        </div>
                        <input type="hidden" value="<?php $postId = $post['id']; ?>">

                        <div class="post">
                            <div class="content">
                                <div class="postSection">
                                    <div class="likeSection" ?>
                                        <div class="likeCounter">
                                            <p class="numberOfLikes" data-id="<?= $post['id']; ?>"><?= numberOfLikes($pdo, $post['id']) ?> </p>
                                        </div>

                                        <?php if (isset($_SESSION['user'])) : ?>
                                            <form class="like postForm" action="app/posts/likes.php" method="post">
                                                <input type="hidden" id="postId" name="like" value="<?= $post['id']; ?>"></input>
                                                <?php if (isLiked($pdo, $_SESSION['user']['id'], $post['id'])) : ?>
                                                    <button style="background-color: blue;" class=" likeBtn" type="submit" value="Submit" data-id="<?= $post['id']; ?>"></button>
                                                <?php else : ?>
                                                    <button style="background-color: grey;" class="likeBtn" type="submit" value="Submit" data-id="<?= $post['id']; ?>"></button>
                                                <?php endif; ?>
                                            </form>
                                        <?php else : ?>
                                            <form class="likeOffline" action="app/posts/likes.php" method="post">
                                                <button name="likeOffline" class="likeBtnOffline" style="background-color: grey;"></button>
                                            </form>
                                        <?php endif; ?>

                                    </div>
                                    <!-- Add comment -->
                                    <?php if (isset($_SESSION['user']['id'])) : ?>
                                        <input type="hidden" name="postId" value="<?php echo $postId; ?>">
                                        <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                                        <div class="commentAndChangePost">
                                            <div class="addComment">
                                                <a href="createComment.php?id=<?php echo $post['id']; ?>">Comment</a>
                                            </div>
                                        <?php endif; ?>

                                        <!-- edit and delete post -->
                                        <?php if (isset($_SESSION['user']['id'])) : ?>
                                            <?php if ($post['userId'] == $_SESSION['user']['id']) : ?>

                                                <div class="editPost">
                                                    <a href="updatePost.php?id=<?php echo $post['id']; ?>"> Edit</a>
                                                </div>
                                                <div class="deletePost">
                                                    <a href="app/posts/delete.php?id=<?php echo $post['id']; ?>">Delete</a>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        </div>
                                </div>

                                <div class="ulComments">

                                    <?php $statement = $pdo->query('SELECT * FROM comments
                                                                     INNER JOIN users ON users.id = comments.userId
                                                                    WHERE comments.postId = :postId ;');
                                    ?>
                                    <?php
                                    if (!$statement) {
                                        die(var_dump($pdo->errorInfo()));
                                    } ?>

                                    <?php $statement->bindParam(':postId', $postId, PDO::PARAM_INT); ?>

                                    <?php $statement->execute(); ?>

                                    <?php $comments = $statement->fetchAll(PDO::FETCH_ASSOC); ?>


                                    <?php foreach ($comments as $comment) : ?>

                                        <input type="hidden" name="commentId" value="<?php echo $comment['commentId']; ?>">

                                        <input type="hidden" name="postId" value="<?php echo $postId; ?>">

                                        <input type="hidden" name="userId" value="<?php echo $comment['userId']; ?>">

                                        <div class="author">
                                            <div class="userImage">
                                                <?php if (!$comment['avatar']) : ?>
                                                    <img src="/assets/img/avatar.png" alt="default profile image">
                                                <?php else : ?>
                                                    <?php if (file_exists(__DIR__ . '/app/users/uploads/' . $comment['avatar'])) : ?>
                                                        <img src=" <?php echo '/app/users/uploads/' . $comment['avatar']; ?>" alt="user's profile image">
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="user">
                                                <?php if (!$comment['firstName']) : ?>
                                                    <?php echo 'Unknown'; ?>
                                                <?php else : ?>
                                                    <?php echo $comment['firstName'] . ' ' . $comment['lastName']; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>


                                        <div class="commentContent">
                                            <?php echo $comment['comment']; ?>
                                        </div>
                                        <div class="likeCounterComment">
                                            <p class="numberOfLikesComments" data-id="<?php echo $comment['commentId']; ?>"><?= numberOfLikesComments($pdo, $comment['commentId']) ?> </p>
                                        </div>
                                        <?php if (isset($_SESSION['user'])) : ?>
                                            <form class="like commentForm" action="app/comments/likes.php" method="post">

                                                <input type="hidden" id="commentId" name="like" value="<?php echo $comment['commentId']; ?>"></input>



                                                <?php if (isLikedComment($pdo, $_SESSION['user']['id'], $comment['commentId'])) : ?>

                                                    <button style="background-color: blue;" class=" clikeBtn" type="submit" value="Submit" data-id="<?php echo $comment['commentId']; ?>"></button>
                                                <?php else : ?>
                                                    <button style="background-color: grey;" class="clikeBtn" type="submit" value="Submit" data-id="<?php echo $comment['commentId']; ?>"></button>
                                                <?php endif; ?>
                                            </form>
                                        <?php else : ?>
                                            <form class="likeOffline" action="app/comments/likes.php" method="post">
                                                <button name="likeOffline" class="likeBtnOffline" style="background-color: grey;"></button>
                                            </form>
                                        <?php endif; ?>


                                        <div class="commentTime">
                                            <time class="text-secondary"><?= formatDate($comment['createdAt']); ?></time>
                                        </div>
                                        <div class="commentEdit">
                                            <?php if (isset($_SESSION['user']['id'])) : ?>
                                                <input type="hidden" name="postId" value="<?php echo $postId; ?>">
                                                <input type="hidden" name="userId" value="<?php echo $userId; ?>">

                                                <?php if ($comment['userId'] == $_SESSION['user']['id']) : ?>
                                                    <a href="app/comments/delete.php?commentId=<?php echo $comment['commentId']; ?>">Delete</a>
                                                    <a href="updateComment.php?commentId=<?php echo $comment['commentId']; ?>">Edit</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>





                                        <!-- REPLIES STARTING-->
                                        <?php
                                        // get replies and the commenter name
                                        $commentId = $comment['commentId'];
                                        $statement = $pdo->query('SELECT replies.*, users.firstName FROM replies  JOIN users ON replies.user_id = users.id WHERE replies.comment_id = :commentId ORDER BY replies.created_at DESC');
                                        if (!$statement) {
                                            die(var_dump($pdo->errorInfo()));
                                        }
                                        $statement->bindParam(':commentId', $commentId, PDO::PARAM_INT);
                                        $statement->execute();
                                        $replies = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        ?>

                                        <div class="replies">

                                            <!-- shows a success message if the reply was posted/edited/deleted succesfully -->
                                            <?php if (isset($_SESSION['success'])) : ?>
                                                <div class="success-message">
                                                    <?php foreach ($_SESSION['success'] as $successMessage) : ?>
                                                        <p><?= $successMessage; ?></p>
                                                    <?php endforeach; ?>
                                                    <?php unset($_SESSION['success']); ?>
                                                </div>
                                            <?php endif; ?>

                                            <!-- REPLIES FORM -->

                                            <div class="replies-form">
                                                <?php if (isset($_SESSION['user'])) : ?>
                                                    <form action="app/replies/store.php" method="post">
                                                        <input type="hidden" id="commentId" name="commentId" value="<?= $comment['commentId']; ?>">
                                                        <input type="hidden" id="postId" name="postId" value="<?= $postId; ?>">
                                                        <div class="form-group">
                                                            <label for="comment"></label>
                                                            <textarea class="form-control" type="text" name="reply" id="comment" placeholder="Add reply" rows="3" required></textarea>
                                                        </div>

                                                        <button class="add-comment-btn" type="submit" name="add-reply">Reply</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div><!-- end of replies-form -->

                                            <!-- DISPLAY REPLIES -->
                                            <?php foreach ($replies as $reply) : ?>
                                                <hr class="reply-divider">
                                                <div class="display-replies">
                                                    <p><?= $reply['firstName']; ?> replied:</p>
                                                    <p><?= $reply['content']; ?></p>
                                                    <small><?= $reply['created_at']; ?></small>

                                                </div><!-- end of display-replies -->


                                                <?php if (isset($_SESSION['user'])) : ?>
                                                    <?php if ($reply['user_id'] == $_SESSION['user']['id']) : ?>
                                                        <div class="edit-reply-forms">
                                                            <!-- EDIT REPLY -->

                                                            <div class="replies-edit-form">
                                                                <form action="app/replies/edit.php?id=<?= $reply['id']; ?>" method="post">
                                                                    <input type="hidden" id="id" name="id" value="<?= $reply['id']; ?>">
                                                                    <input type="hidden" id="commentId" name="commentId" value="<?= $comment['commentId']; ?>">
                                                                    <div class="form-group">
                                                                        <label for="content">Edit reply:</label>
                                                                        <input name="content" id="content" class="form-control" value="<?= $reply['content'] ?>" required></input>
                                                                    </div><!-- /form-group -->
                                                                    <button type="submit" class="edit-btn">Edit</button>
                                                                </form>
                                                            </div>

                                                            <!-- DELETE REPLY -->

                                                            <div class="replies-edit-form">

                                                                <form action="app/replies/delete.php?id=<?= $reply['id']; ?>" method="post">

                                                                    <input type="hidden" id="id" name="id" value="<?= $reply['id']; ?>">
                                                                    <input type="hidden" id="commentId" name="commentId" value="<?= $comment['commentId']; ?>">


                                                                    <button type="submit" class="delete-btn">Delete</button>
                                                                </form>
                                                            </div>

                                                        </div><!-- end of edit-reply-form -->
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <hr class="reply-divider">
                                            <?php endforeach; ?>

                                        </div> <!-- end of replies -->

                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>

        </div>


    </article>
</div>
<?php require __DIR__ . '/views/footer.php'; ?>
