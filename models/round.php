<?php
    require_once 'models/creature.php';

    class Round extends ActiveRecordBase {
        public $creatures = []; // dictionary from creatureid to creature
        public $id;
        public $game;
        public $errors = []; // dictionary from userid to list of errors

        public function error( $userid, $description, $actual = '', $expected = '' ) {
            if ( !isset( $this->errors[ $userid ] ) ) {
                $this->errors[ $userid ] = [];
            }
            $this->errors[ $userid ][] = [
                'description' => $description,
                'actual' => $actual,
                'expected' => $expected
            ];
        }
        public function __construct( $a = false, $b = false, $genesis = false ) {
            if ( $a instanceof Round ) {
                // Clone from existing round: new Round( $oldRound )
                $oldRound = $a;
                $this->game = $oldRound->game;
                $this->id = $oldRound->id + 1;
                foreach ( $oldRound->creatures as $creature ) {
                    $this->creatures[ $creature->id ] = clone $creature;
                    $this->creatures[ $creature->id ]->round = $this;
                }
            }
            else if ( $a !== false && $b !== false ) {
                // find the whole round from database: new Round( $game, $id );
                $game = $a;
                $id = $b;
                $this->exists = true;
                $this->id = $id;
                $this->game = $game;
                $gameid = $game->id;
                $roundid = $id;
                $creatures_info = dbSelect(
                    'roundcreatures',
                    [ 'creatureid', 'action', 'direction', 'hp', 'locationx', 'locationy' ],
                    compact( 'roundid', 'gameid' )
                );
                // map: creatureid => user
                $usersMap = [];
                // genesis is optional, if you want the constructor to run faster
                if ( $genesis ) {
                    foreach ( $genesis->creatures as $creature ) {
                        if ( !isset( $usersMap[ $creature->id ] ) ) {
                            $usersMap[ $creature->id ] = $creature->user;
                        }
                    }
                }
                foreach ( $creatures_info as $i => $creature_info ) {
                    $creature = new Creature( $creature_info );
                    $creature->game = $game;
                    $creature->round = $this;
                    if ( isset( $usersMap[ $creature->id ] ) ) {
                        $creature->user = $usersMap[ $creature->id ];
                    }
                    else {
                        $id = $creature_info[ 'creatureid' ];
                        $user_info = dbSelectOne(
                            'creatures',
                            [ 'userid' ],
                            compact( 'id', 'gameid' )
                        );
                        $user = new User( $user_info[ 'userid' ] );
                        $creature->user = $user;
                    }
                    $this->creatures[ $creature->id ] = $creature;
                }
            }
        }
        protected function getUsersAlive() {
            $usersAlive = [];
            foreach ( $this->creatures as $creature ) {
                if ( $creature->alive && isset( $creature->user ) ) {
                    $usersAlive[ $creature->user->id ] = $creature->user;
                }
            }
            return $usersAlive;
        }
        public function isFinalRound() {
            $usersAlive = $this->getUsersAlive();

            return count( $usersAlive ) <= 1;
        }
        public function getWinnerId() {
            if ( !$this->isFinalRound() ) {
                throw new ModelValidationException( 'There is no winner if the game is not over' );
            }
            $usersAlive = $this->getUsersAlive();
            if ( !empty( $usersAlive ) ) {
                return end( $usersAlive )->id;
            }
            return false;
        }

        protected function create() {
            assert( $this->game instanceof Game/*, '$this->game must be an instance of Game when a round is created'*/ );

            $rows = [];
            $gameid = $this->game->id;
            $roundid = $this->id;
            foreach ( $this->creatures as $creature ) {
                $locationx = $creature->locationx;
                $locationy = $creature->locationy;
                $hp = $creature->hp;
                $direction = directionConstToString( $creature->intent->direction );
                $action = actionConstToString( $creature->intent->action );
                $creatureid = $creature->id;
                $rows[] = compact( 'gameid', 'roundid', 'locationx', 'locationy', 'hp', 'action', 'direction', 'creatureid' );
            }

            dbInsertMulti( 'roundcreatures', $rows );
        }

        public function onBeforeSave() {
            foreach ( $this->creatures as $creature ) {
                if ( !isset( $creature->hp ) ) {
                    $creature->hp = 0;
                }
            }
        }
    }
?>
