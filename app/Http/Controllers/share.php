public function importsave(Request $request)
{
   if($request->hasFile('excel'))
    {
        $path = $request->file('excel')->getRealPath();
        $data= Excel::load($path, function($reader) {})->get();
        if(!empty($data) && $data->count())
        {
            foreach($data->toArray() as $key=>$value)
            {
                if(!empty($value))
                {
                    Employee::insert($value);
                }
            }
        }
    }
}