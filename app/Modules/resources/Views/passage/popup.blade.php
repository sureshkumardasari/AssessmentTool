      <div id="popup_data">
      <div class="form-group ">
       Institution:
       
        {{$institution['name']}}
        </select>
       </div>
      </div>
      <div class="form-group ">
       Category:
       
       {{$category['name']}}
       
      </div>
      <div class="form-group ">
       Subject:
      
        {{$subject['name']}}
       
      </div>
      <div class="form-group ">
       Lessons:
        {{$lessons['name']}}
       
      </div>
      


      <div class="form-group ">
       Passage Title:
       
       {{ strip_tags(htmlspecialchars_decode($passage['passagetitle'])) }}
       </div>
      </div>
      <div class="form-group ">
       Passage Text:
       {{ strip_tags(htmlspecialchars_decode($passage['passagetext']))}}
       
      </div>
       <div class="form-group ">
      Passage Lines:
       {{ strip_tags(htmlspecialchars_decode($passage['passagelines']))}}
       
      </div>
      
      </div>
      
       </div>
      </div>

