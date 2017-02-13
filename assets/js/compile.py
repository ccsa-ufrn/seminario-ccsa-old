import shlex, subprocess, sys, string, json

jsonvar = open('config.json').read()
pyjson = json.loads(jsonvar)

for f in pyjson['files'] :
    
    if sys.platform.startswith('linux') :
        print('linux')
    elif sys.platform.startswith('win32') :
        str = 'java -jar compiler.jar --js '
        
        for v in f['vendor'] :
            str += 'vendor\\'+v+' '
            
        for c in f['custom'] :
            str += 'custom\\'+c+' ' 

        str += ' --js_output_file '+f['outputname']
        temp = subprocess.call(str)



