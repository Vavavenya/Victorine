<?php
/**
 * Created by PhpStorm.
 * User: Боря
 * Date: 13.05.2018
 * Time: 10:17
 */

namespace liw;

use App\Entity\Player;
use App\Entity\Question;



class TokenEditor
{
    public function tokenCreate($player,$question,$slug,$id)
    {
        if( !$player && !$question){
            $slug3=$slug;
            $slug3++;
            return $this->redirectToRoute('quiz_question', array('id' => $id,'slug' => $slug3,));
        } else {
            $slug2=$slug;
            while(isset($player)) {
                $slug2++;
                $question= $this->getDoctrine()
                    ->getRepository(Question::class)
                    ->findOneBy(['quiz' => $id,
                        'id' => $slug2,
                    ]);
                if (!$question) {
                    return $this->redirectToRoute('quiz_question', array('id' => $id,'slug' => $slug2,));
                    break;
                }
                $player= $this->getDoctrine()
                    ->getRepository(Player::class)
                    ->findOneBy(['quiz' => $id,
                        'question' => $slug2
                    ]);
                if (!$player) {
                    return $this->redirectToRoute('quiz_question', array('id' => $id,'slug' => $slug2,));
                    break;
                }
                }
            }

    }

}