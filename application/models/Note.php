<?php

class NoteModel
{
    /**
     * 节下的笔记
     */
    public function getSectionNote($sectionId)
    {
        $tblNote = new DB_Haodu_CourseNote();
        $noteList = $tblNote->fetchAll("*", "where section_id=$sectionId");

        if ($noteList) {
            foreach ($noteList as &$note) {
                $note['image'] = Common_Config::NOTE_BASE_URL . $note['image'];
            }
            unset($note);
        }
        return $noteList;
    }

    public function getSectionNoteNum($sectionId)
    {
        $tblNote = new DB_Haodu_CourseNote();
        return $tblNote->queryCount("where section_id=$sectionId");
    }
}