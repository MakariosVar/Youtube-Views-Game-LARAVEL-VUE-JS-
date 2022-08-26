<?php

namespace App\Http\Controllers;
use App\Models\video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
 
    public function index(){
        $videos = video::inRandomOrder()->get();
        $videosCount = $videos->count();

        return view('VideosOnServer', compact('videos', 'videosCount'));
    }

    public function getRandomVideo(){
        $video = video::inRandomOrder()->first();
        $video->title = htmlspecialchars_decode($video->title);
        $video->captureDate = date( "d-m-Y", strtotime($video->updated_at) );
        return  $video;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
 
        $data = request()->validate([
            'id' => 'required',
            'title' => 'required' ,
            'thumbnail' => 'required',
            'views' => 'required',
           
        ]);
    
        

        

        

           
        
     
        video::create([
            'videoID' => $data['id'],
            'title' => $data['title'],
            'thumbnail' => $data['thumbnail'],
            'views' => $data['views'],
                   
        ]);

        return response()->json([
            'status'   => 'success'
          ]); 
    }

    public function generate(Request $request){
        $api_key=env('YTB_API_KEY');
            $q = $request['query'];
            try{
                $apiData = @file_get_contents('https://www.googleapis.com/youtube/v3/search?part=snippet&q='.$q.'&maxResults=1&key='.$api_key);
                $apiError = 'video not found';
                if($apiData){
                    $video = json_decode($apiData);
                }else{
                   return('invalide api or somethink');
                }
            }catch(Exception $e){
                $apiError = $e->getMessage();
            }
           
            $id = $video->items[0]->id->videoId;
            $title = $video->items[0]->snippet->title;
            $thumbnail = $video->items[0]->snippet->thumbnails->high->url;

            try{
                $apiData = @file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$id.'&part=statistics&key='.$api_key);
                $apiError = 'video not found';
                if($apiData){
                    $videoStat = json_decode($apiData);
                }else{
                   return('invalide api or somethink');
                }
            }catch(Exception $e){
                $apiError = $e->getMessage();
            }

            $views =  $videoStat->items[0]->statistics->viewCount;
          
            video::create([
                'videoID' => $id,
                'title' => $title,
                'thumbnail' => $thumbnail,
                'views' => $views,
                       
            ]);
    
            return response()->json([
                'status'   => 'success'
              ]); 

            
    }

}
