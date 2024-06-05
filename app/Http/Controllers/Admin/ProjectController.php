<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Type;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator; //Illuminate\Suppor\Facades\Validator
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Type::all();
        return view('admin.projects.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // $form_data = $request->all();
        $form_data = $this->validation($request->all());
        $form_data['slug'] = Project::generateSlug($form_data['title']);
       
        if ($request->hasFile('image')) {
            $path = Storage::put('project_images', $request->image);
            $form_data['image'] = $path;
        }
        $newProject = Project::create($form_data);
        return redirect()->route('admin.projects.show', $newProject->slug)->with('message', $form_data['title'] . ' è stato creato');

    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $types = Type::all();
        return view('admin.projects.edit', compact('project,types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        // $form_data = $request->all();
        $form_data = $this->validation($request->all());
        if ($project->title !== $form_data['title']) {
            $form_data['slug'] = Project::generateSlug($form_data['title']);
        }
        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::delete($project->image);
            }
            $path = Storage::put('project_images', $request->image);
            $form_data['image'] = $path;
        }
        $project->update($form_data);
        return redirect()->route('admin.projects.show', compact('project'))->with('message', $project->title . ' è stato editato');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::delete($project->image);
        }
        $project->delete();
        return redirect()->route('admin.projects.index')->with('message', $project->title . ' è stato eliminato');
    }


    public function validation($data)
    {
        //dd($data);
        $validator = Validator::make(
            $data,
            [
                'title' => [
                    'required',
                    'max:200',
                    'min:3',
                    
                ],
                'image' => 'nullable|max:255|image',
                'content' => 'required',
            ],
            [
                'title.required' => 'Campo obbligatorio',
                'title.unique' => 'Progetto già esistente',
                'title.max' => 'Il titolo deve avere :max caratteri',
                'title.min' => 'Il titolo deve avere :min caratteri',
                'image.max' => 'L\'immagine deve contenere :max caratteri',
                'content.required' => 'Campo obbligatorio'
            ]
        )->validate();
        return $validator;
    }
}
