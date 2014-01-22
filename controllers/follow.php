<?php
    include_once 'models/follow.php';
    class FollowController extends ControllerBase {
        public function create( $followerid, $followedid ) {
            $follow = new Follow(); 
            $followerid = intval( $followerid );
            $followedid = intval( $followedid );
            $follow->followerid = $followerid;
            $follow->followedid = $followedid;
            $follow->save();
            go(); 
        }

        public function delete( $followerid, $followedid ) {
            $follow = new Follow( $followerid, $followedid );
            $follow->delete();
            go();
        }
    }
?>
