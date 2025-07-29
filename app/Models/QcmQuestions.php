<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcmQuestions extends Model
{
    use HasFactory;

    protected $table = "qcm_questions";
    protected $primaryKey = "idQuestion";

    protected $fillable = [
        'idQCM',
        'texteQuestion',
    ];

    // Relation avec le modèle QCM
    public function rel_qcm_question()
    {
        return $this->belongsTo(Qcm::class, 'idQCM');
    }

    // Relation avec le modèle QcmReponses 
    public function reponses_questions()
    {
        return $this->hasMany(QcmReponses::class, 'idQuestion');
    }
}
