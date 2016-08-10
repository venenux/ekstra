#!/bin/bash

# part 1 decompress and remove guindo files

dirtempo=$(pwd)/unzippedv3
rm -fr $dirtempo
mkdir -p $dirtempo
unzip v3*_JDK*.zip -d $dirtempo
rm $dirtempo/v3*_JDK*/*.bat
rm -r $dirtempo/v3*_JDK*/vbscripts_tests
rm -r $dirtempo/v3*_JDK*/comm/*indow*
rm $dirtempo/v3*_JDK*/conf/wrapper_net.ini
rm $dirtempo/v3*_JDK*/conf/vposnetinst.ini
rm -r $dirtempo/v3*_JDK*/lib/*indow*
rm $dirtempo/v3*_JDK*/lib/*inRegistry*.jar
#rm -r $dirtempo/v3*_JDK*/conf_olbpos
#rm $dirtempo/v3*_JDK*/lib/olb*.jar
rm $dirtempo/v3*_JDK*/lib/*.dll
rm $dirtempo/v3*_JDK*/lib/*.exe
rm $dirtempo/v3*_JDK*/*.exe
rm $dirtempo/v3*_JDK*/*.reg
rm -r $dirtempo/v3*_JDK*/config
rm -r $dirtempo/v3*_JDK*/conf/Imagenes*/msc.ico
rm -r $dirtempo/v3*_JDK*/conf/Imagenes*/Thumbs.db

# part 2 convert files to unix/linux path and set home to /opt/vposmegasoft
# recomendado leer: http://www.tutorialspoint.com/unix/unix-regular-expressions.htm

sed -e "s/\\\/\\//g" -e "s|path=C\\:|path=/opt/vposmegasoft|" -e "s|pathtimer=C\\:|pathtimer=/opt/vposmegasoft|" -i $dirtempo/v3*_JDK*/conf/vposconf.ini

sed -e "s/\\\/\\//g" -i $dirtempo/v3*_JDK*/conf/vposuniversal.ini

sed -e "s/ultvoucherOfcr/UltVoucherOfcr/" -i $dirtempo/v3*_JDK*/conf/vposuniversal.ini

sed -e "s|/voucher/logs/vpos.log|/opt/vposmegasoft/voucher/logs/vpos.log|" -e "s|/voucher/logs/vposerr.log|/opt/vposmegasoft/voucher/logs/errorvpos.log|" -i $dirtempo/v3*_JDK*/conf/log4j.xml

sed -e "s|/voucher/logs/wrapper.log|/opt/vposmegasoft/voucher/logs/wrapper.log|" -e "s|/voucher/logs/wrapper-error.log|/opt/vposmegasoft/voucher/logs/wrapper-error.log|" -i $dirtempo/v3*_JDK*/conf/log4j.xml

sed -e "s|C\\:/voucher/ejecutar|/opt/vposmegasoft/voucher/peticion|" -e "s|C\\:/voucher/respuesta|/opt/vposmegasoft/voucher/respuesta|" -i $dirtempo/v3*_JDK*/conf/integracionArchivos.properties

# part 3 activacion megavpos modo stand alone, desactivar al arrancar

sed -e "s/Megavpos=.*/Megavpos=0/" -i $dirtempo/v3*_JDK*/conf/vposuniversal.ini

# part 4 set to lowercase all references and files in config and imagefiles, version 9.10.X dont need this hack

sed -e "s/SelImpresora.jpg/Selimpresora.JPG/" -i $dirtempo/v3*_JDK*/conf/vposuniversal.ini

# part 5: configure the vpost wiuthout using the gui stupid! TODO pendiente un patron en puerto en vez de cableado

sed -e "s/^id=.*/id=01/" -i $dirtempo/v3*_JDK*/conf/vposconf.ini
sed -e "s|^puerto=.*|puerto=/dev/ttyS98|" -i $dirtempo/v3*_JDK*/conf/vposconf.ini
sed -e "s/^velocidad=.*/velocidad=9600/" -i $dirtempo/v3*_JDK*/conf/vposconf.ini
sed -e "s|^root=./lib/windows/.*|root=./lib/|" -i $dirtempo/v3*_JDK*/conf/vposconf.ini

# part 6 set vtid (only for testing env) for production this are set by lavka

sed -e "s/host=/host=200.71.151.226/" -i $dirtempo/v3*_JDK*/conf/vposconf.ini
sed -e "s/^port=.*/port=24300/" -i $dirtempo/v3*_JDK*/conf/vposconf.ini
sed -e "s/^vtid=.*/vtid=tije001/" -i $dirtempo/v3*_JDK*/conf/vposconf.ini

# finish remove the files of the temporally

filenamenew=$(cd $dirtempo*;ls | grep v3*JDK*1.5*)

echo "mv $dirtempo/v3*_JDK*1.5* $filenamenew"
mv $dirtempo/v3*_JDK*1.5* $filenamenew
echo "tar -czvf $filenamenew.tar.gz $filenamenew"
tar -czvf vpos-megasoft-$filenamenew.tar.gz $filenamenew

rm -r $filenamenew
rm -r $dirtempo
